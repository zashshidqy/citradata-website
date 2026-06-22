<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();
requireLogin();

$pdo      = getDbConnection();
$userRole = $_SESSION['user_role'] ?? 'trial';

$sectors  = ['Residential','Office','Retail','Industrial','Hospitality','Hospital','Mixed Use','Infrastructure','Other'];
$statuses = ['Planning','Design','Tender','Construction','Completed','On Hold'];

// Filter params
$search  = trim($_GET['q']      ?? '');
$fSector = $_GET['sector']      ?? '';
$fStatus = $_GET['status']      ?? '';
$fCity   = trim($_GET['city']   ?? '');
$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;

// Dynamic city list
$cities = $pdo->query("SELECT DISTINCT location_city FROM projects WHERE is_active=1 AND location_city IS NOT NULL AND location_city<>'' ORDER BY location_city ASC")->fetchAll(PDO::FETCH_COLUMN);

// Build query
$where  = ['is_active = 1'];
$params = [];

if ($search !== '') {
    $where[]       = '(project_name LIKE :q OR location_city LIKE :q2 OR developer_name LIKE :q3)';
    $params[':q']  = "%$search%";
    $params[':q2'] = "%$search%";
    $params[':q3'] = "%$search%";
}
if ($fSector !== '') { $where[] = 'sector = :sec';       $params[':sec']  = $fSector; }
if ($fStatus !== '') { $where[] = 'status = :st';        $params[':st']   = $fStatus; }
if ($fCity   !== '') { $where[] = 'location_city = :city'; $params[':city'] = $fCity; }

$whereStr = implode(' AND ', $where);

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE $whereStr");
$countStmt->execute($params);
$totalCount = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($totalCount / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

$dataStmt = $pdo->prepare("SELECT * FROM projects WHERE $whereStr ORDER BY is_featured DESC, created_at DESC LIMIT :lim OFFSET :off");
foreach ($params as $k => $v) $dataStmt->bindValue($k, $v);
$dataStmt->bindValue(':lim',  $perPage, PDO::PARAM_INT);
$dataStmt->bindValue(':off',  $offset,  PDO::PARAM_INT);
$dataStmt->execute();
$projects = $dataStmt->fetchAll();

// Flash message from export redirect
$exportError = $_SESSION['export_error'] ?? null;
unset($_SESSION['export_error']);

function fmtValueShort(?int $val): string {
    if ($val === null) return '';
    if ($val >= 1_000_000_000) return 'Rp ' . number_format($val / 1_000_000_000, 1, ',', '.') . ' Miliar';
    if ($val >= 1_000_000)     return 'Rp ' . number_format($val / 1_000_000, 0, ',', '.')     . ' Juta';
    return 'Rp ' . number_format($val, 0, ',', '.');
}

function sectorBadgeClass(string $sector): string {
    return match($sector) {
        'Residential'    => 'bg-blue-100 text-blue-700',
        'Office'         => 'bg-slate-200 text-slate-700',
        'Retail'         => 'bg-purple-100 text-purple-700',
        'Industrial'     => 'bg-orange-100 text-orange-700',
        'Hospitality'    => 'bg-pink-100 text-pink-700',
        'Hospital'       => 'bg-red-100 text-red-700',
        'Mixed Use'      => 'bg-teal-100 text-teal-700',
        'Infrastructure' => 'bg-amber-100 text-amber-700',
        default          => 'bg-gray-100 text-gray-600',
    };
}

function statusBadgeClass(string $status): string {
    return match($status) {
        'Planning'     => 'bg-blue-50 text-blue-700 border border-blue-200',
        'Design'       => 'bg-purple-50 text-purple-700 border border-purple-200',
        'Tender'       => 'bg-amber-50 text-amber-700 border border-amber-200',
        'Construction' => 'bg-green-50 text-green-700 border border-green-200',
        'Completed'    => 'bg-slate-100 text-slate-500 border border-slate-200',
        'On Hold'      => 'bg-red-50 text-red-600 border border-red-200',
        default        => 'bg-slate-100 text-slate-500 border border-slate-200',
    };
}

$filterQs  = http_build_query(array_filter(['q'=>$search,'sector'=>$fSector,'status'=>$fStatus,'city'=>$fCity]));
$pageTitle = 'Find Projects – Citradata';
$useSwiper = false;

require __DIR__ . '/../includes/head.php';
?>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">
<?php require __DIR__ . '/../includes/nav.php'; ?>

<main class="flex-grow py-10 px-4">
<div class="max-w-7xl mx-auto">

    <!-- Page header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Find Projects</h1>
            <p class="text-slate-500 text-sm mt-1">Browse active construction projects across Indonesia</p>
        </div>
        <?php if (in_array($userRole, ['member','admin'])): ?>
        <a href="<?php echo url('pages/projects_export.php?' . $filterQs); ?>"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-colors shadow-sm">
            <i class="fas fa-file-csv"></i> Export CSV
        </a>
        <?php endif; ?>
    </div>

    <?php if ($exportError): ?>
    <div class="mb-5 bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-2xl text-sm font-medium flex items-center gap-3">
        <i class="fas fa-lock"></i> <?php echo e($exportError); ?>
    </div>
    <?php endif; ?>

    <!-- Trial banner -->
    <?php if ($userRole === 'trial'): ?>
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-4">
        <i class="fas fa-lock text-amber-500 text-xl mt-0.5 shrink-0"></i>
        <div>
            <p class="font-semibold text-amber-800 text-sm">Free Trial Access</p>
            <p class="text-amber-700 text-xs mt-1">
                Contact details (phone, email, contact person) are hidden on trial access.
                <a href="<?php echo url('pages/contact.php'); ?>" class="underline font-semibold">Contact us</a> to upgrade to Member and unlock all data.
            </p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Search & Filters -->
    <form method="GET" action="<?php echo url('pages/projects.php'); ?>"
          class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 mb-6 flex flex-wrap gap-3 items-end">
        <div class="flex-grow min-w-[180px]">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Search</label>
            <input type="text" name="q" value="<?php echo e($search); ?>" placeholder="Project name, city, developer…"
                   class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Sector</label>
            <select name="sector" class="h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                <option value="">All Sectors</option>
                <?php foreach ($sectors as $s): ?>
                <option value="<?php echo e($s); ?>" <?php echo $fSector === $s ? 'selected' : ''; ?>><?php echo e($s); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Status</label>
            <select name="status" class="h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                <option value="">All Statuses</option>
                <?php foreach ($statuses as $s): ?>
                <option value="<?php echo e($s); ?>" <?php echo $fStatus === $s ? 'selected' : ''; ?>><?php echo e($s); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">City</label>
            <select name="city" class="h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                <option value="">All Cities</option>
                <?php foreach ($cities as $c): ?>
                <option value="<?php echo e($c); ?>" <?php echo $fCity === $c ? 'selected' : ''; ?>><?php echo e($c); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="h-11 px-6 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">Search</button>
        <?php if ($search || $fSector || $fStatus || $fCity): ?>
        <a href="<?php echo url('pages/projects.php'); ?>" class="h-11 px-4 flex items-center bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
            <i class="fas fa-times mr-1.5 text-xs"></i> Clear
        </a>
        <?php endif; ?>
    </form>

    <!-- Results count -->
    <p class="text-sm text-slate-500 mb-5">
        Menampilkan <span class="font-semibold text-slate-700"><?php echo $totalCount; ?></span> proyek
        <?php if ($search || $fSector || $fStatus || $fCity): ?>
        <span class="text-slate-400">(filtered)</span>
        <?php endif; ?>
    </p>

    <!-- Project Grid -->
    <?php if (empty($projects)): ?>
    <div class="bg-white rounded-2xl p-16 text-center border border-slate-100 shadow-sm">
        <i class="fas fa-building text-4xl text-slate-200 mb-4 block"></i>
        <p class="text-slate-400 font-medium">Tidak ada proyek yang sesuai filter.</p>
        <a href="<?php echo url('pages/projects.php'); ?>" class="mt-4 inline-block text-brandBlue text-sm hover:underline">Reset filter</a>
    </div>
    <?php else: ?>
    <div class="grid md:grid-cols-2 gap-5 mb-10">
        <?php foreach ($projects as $p): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 flex flex-col">
            <div class="p-6 flex-grow">
                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold <?php echo sectorBadgeClass($p['sector']); ?>">
                        <?php echo e($p['sector']); ?>
                    </span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold <?php echo statusBadgeClass($p['status']); ?>">
                        <?php echo e($p['status']); ?>
                    </span>
                    <?php if ($p['is_featured']): ?>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200">
                        <i class="fas fa-star text-[10px] mr-1"></i>Featured
                    </span>
                    <?php endif; ?>
                </div>

                <h2 class="font-bold text-slate-900 text-base leading-snug mb-4"><?php echo e($p['project_name']); ?></h2>

                <div class="space-y-2 text-sm">
                    <?php if ($p['location_city']): ?>
                    <p class="flex items-center gap-2 text-slate-600">
                        <i class="fas fa-map-marker-alt text-brandBlue w-4 shrink-0 text-center"></i>
                        <?php echo e($p['location_city']); ?>
                    </p>
                    <?php endif; ?>
                    <?php if ($p['developer_name']): ?>
                    <p class="flex items-center gap-2 text-slate-600">
                        <i class="fas fa-user-tie text-slate-400 w-4 shrink-0 text-center"></i>
                        <?php echo e($p['developer_name']); ?>
                    </p>
                    <?php endif; ?>
                    <?php if ($p['project_value']): ?>
                    <p class="flex items-center gap-2 text-slate-600">
                        <i class="fas fa-coins text-amber-400 w-4 shrink-0 text-center"></i>
                        <span class="font-semibold text-slate-800"><?php echo fmtValueShort((int)$p['project_value']); ?></span>
                    </p>
                    <?php endif; ?>
                    <?php if ($p['start_date'] || $p['end_date']): ?>
                    <p class="flex items-center gap-2 text-slate-500 text-xs">
                        <i class="fas fa-calendar-alt text-slate-300 w-4 shrink-0 text-center"></i>
                        <?php
                        $dates = [];
                        if ($p['start_date']) $dates[] = date('M Y', strtotime($p['start_date']));
                        if ($p['end_date'])   $dates[] = date('M Y', strtotime($p['end_date']));
                        echo e(implode(' – ', $dates));
                        ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
                <span class="text-xs text-slate-400"><?php echo date('d M Y', strtotime($p['created_at'])); ?></span>
                <a href="<?php echo url('pages/project_detail.php?id=' . $p['id']); ?>"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-brandBlue text-white text-xs font-semibold rounded-xl hover:bg-blue-800 transition-colors">
                    View Detail <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="flex justify-center gap-2 mt-4 mb-8 flex-wrap">
        <?php for ($i = 1; $i <= $totalPages; $i++):
            $qs = http_build_query(array_filter(['q'=>$search,'sector'=>$fSector,'status'=>$fStatus,'city'=>$fCity,'page'=>$i]));
        ?>
        <a href="?<?php echo htmlspecialchars($qs); ?>"
           class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-semibold transition-colors
                  <?php echo $i === $page ? 'bg-brandBlue text-white shadow' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'; ?>">
            <?php echo $i; ?>
        </a>
        <?php endfor; ?>
    </nav>
    <?php endif; ?>

</div>
</main>

<?php $footerVariant = 'light'; require __DIR__ . '/../includes/footer.php'; ?>
<?php require __DIR__ . '/../includes/scripts.php'; ?>
</body>
</html>
