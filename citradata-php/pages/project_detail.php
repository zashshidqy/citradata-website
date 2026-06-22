<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();
requireLogin();

$pdo      = getDbConnection();
$userRole = $_SESSION['user_role'] ?? 'trial';
$id       = (int)($_GET['id'] ?? 0);

if ($id < 1) { header('Location: ' . url('pages/projects.php')); exit; }

$stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id AND is_active = 1');
$stmt->execute([':id' => $id]);
$p = $stmt->fetch();

if (!$p) { header('Location: ' . url('pages/projects.php')); exit; }

function fmtIDRFull($val): string {
    if ($val === null || $val === '') return '–';
    return 'Rp ' . number_format((int)$val, 0, ',', '.');
}

function sectorBadgeClass(string $s): string {
    return match($s) {
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

function statusBadgeClass(string $s): string {
    return match($s) {
        'Planning'     => 'bg-blue-50 text-blue-700 border border-blue-200',
        'Design'       => 'bg-purple-50 text-purple-700 border border-purple-200',
        'Tender'       => 'bg-amber-50 text-amber-700 border border-amber-200',
        'Construction' => 'bg-green-50 text-green-700 border border-green-200',
        'Completed'    => 'bg-slate-100 text-slate-500 border border-slate-200',
        'On Hold'      => 'bg-red-50 text-red-600 border border-red-200',
        default        => 'bg-slate-100 text-slate-500 border border-slate-200',
    };
}

function sensField(string $value, string $role, string $type = 'text'): string {
    if (!in_array($role, ['member','admin'])) {
        return '<span class="inline-flex items-center gap-1.5 text-slate-400 text-sm">
                    <i class="fas fa-lock text-xs text-slate-300"></i>
                    <span class="tracking-widest font-mono">••••••</span>
                </span>';
    }
    if ($value === '' || $value === null) return '<span class="text-slate-400">–</span>';
    $safe = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    if ($type === 'email') return '<a href="mailto:' . $safe . '" class="text-brandBlue hover:underline">' . $safe . '</a>';
    if ($type === 'phone') {
        $clean = preg_replace('/[^0-9+]/', '', $value);
        return '<a href="tel:' . $clean . '" class="text-brandBlue hover:underline">' . $safe . '</a>';
    }
    return $safe;
}

$pageTitle = e($p['project_name']) . ' – Citradata';
$useSwiper = false;
require __DIR__ . '/../includes/head.php';
?>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">
<?php require __DIR__ . '/../includes/nav.php'; ?>

<main class="flex-grow py-10 px-4">
<div class="max-w-4xl mx-auto">

    <a href="<?php echo url('pages/projects.php'); ?>"
       class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-brandBlue mb-6 transition-colors font-medium">
        <i class="fas fa-arrow-left text-xs"></i> Back to Projects
    </a>

    <!-- Trial CTA -->
    <?php if ($userRole === 'trial'): ?>
    <div class="mb-6 bg-gradient-to-r from-brandBlue to-blue-700 rounded-2xl p-6 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                <i class="fas fa-unlock-alt text-white text-lg"></i>
            </div>
            <div>
                <p class="font-bold text-base">Unlock full contact details</p>
                <p class="text-blue-100 text-sm mt-0.5">Upgrade to Member untuk melihat kontak developer, kontraktor, nomor telepon, dan email.</p>
            </div>
        </div>
        <a href="<?php echo url('pages/contact.php'); ?>"
           class="shrink-0 px-5 py-2.5 bg-white text-brandBlue text-sm font-bold rounded-xl hover:bg-blue-50 transition-colors whitespace-nowrap">
            Contact Us to Upgrade
        </a>
    </div>
    <?php endif; ?>

    <!-- Project Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-7 mb-5">
        <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-3 py-1.5 rounded-xl text-xs font-semibold <?php echo sectorBadgeClass($p['sector']); ?>">
                <?php echo e($p['sector']); ?>
            </span>
            <span class="px-3 py-1.5 rounded-xl text-xs font-semibold <?php echo statusBadgeClass($p['status']); ?>">
                <?php echo e($p['status']); ?>
            </span>
            <?php if ($p['is_featured']): ?>
            <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200">
                <i class="fas fa-star text-[10px] mr-1"></i>Featured
            </span>
            <?php endif; ?>
        </div>

        <h1 class="text-2xl font-bold text-slate-900 mb-6 leading-snug"><?php echo e($p['project_name']); ?></h1>

        <div class="grid sm:grid-cols-2 gap-x-8 gap-y-5 text-sm">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Lokasi</p>
                <p class="text-slate-800 font-semibold"><?php echo e($p['location_city'] ?? '–'); ?></p>
                <?php if ($p['location_address']): ?>
                <p class="text-slate-500 text-xs mt-0.5"><?php echo e($p['location_address']); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nilai Proyek</p>
                <p class="text-slate-900 font-bold text-xl"><?php echo fmtIDRFull($p['project_value']); ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal Mulai</p>
                <p class="text-slate-800 font-medium"><?php echo $p['start_date'] ? date('d F Y', strtotime($p['start_date'])) : '–'; ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal Selesai</p>
                <p class="text-slate-800 font-medium"><?php echo $p['end_date'] ? date('d F Y', strtotime($p['end_date'])) : '–'; ?></p>
            </div>
            <?php if ($p['consultant_name']): ?>
            <div class="sm:col-span-2">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Konsultan</p>
                <p class="text-slate-800 font-medium"><?php echo e($p['consultant_name']); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Developer Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-7 mb-5">
        <h2 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="fas fa-user-tie text-brandBlue text-sm"></i>
            </span>
            Developer
        </h2>
        <div class="grid sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Developer</p>
                <p class="text-slate-800 font-semibold"><?php echo e($p['developer_name'] ?? '–'); ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                    Contact Person <?php if ($userRole==='trial'): ?><span class="text-amber-500 font-normal normal-case text-[10px]">• members only</span><?php endif; ?>
                </p>
                <p><?php echo sensField($p['developer_contact'] ?? '', $userRole); ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                    Telepon <?php if ($userRole==='trial'): ?><span class="text-amber-500 font-normal normal-case text-[10px]">• members only</span><?php endif; ?>
                </p>
                <p><?php echo sensField($p['developer_phone'] ?? '', $userRole, 'phone'); ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                    Email <?php if ($userRole==='trial'): ?><span class="text-amber-500 font-normal normal-case text-[10px]">• members only</span><?php endif; ?>
                </p>
                <p><?php echo sensField($p['developer_email'] ?? '', $userRole, 'email'); ?></p>
            </div>
        </div>
    </div>

    <!-- Contractor Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-7 mb-5">
        <h2 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                <i class="fas fa-hard-hat text-orange-500 text-sm"></i>
            </span>
            Kontraktor
        </h2>
        <div class="grid sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Kontraktor</p>
                <p class="text-slate-800 font-semibold"><?php echo e($p['contractor_name'] ?? '–'); ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                    Contact Person <?php if ($userRole==='trial'): ?><span class="text-amber-500 font-normal normal-case text-[10px]">• members only</span><?php endif; ?>
                </p>
                <p><?php echo sensField($p['contractor_contact'] ?? '', $userRole); ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                    Telepon <?php if ($userRole==='trial'): ?><span class="text-amber-500 font-normal normal-case text-[10px]">• members only</span><?php endif; ?>
                </p>
                <p><?php echo sensField($p['contractor_phone'] ?? '', $userRole, 'phone'); ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                    Email <?php if ($userRole==='trial'): ?><span class="text-amber-500 font-normal normal-case text-[10px]">• members only</span><?php endif; ?>
                </p>
                <p><?php echo sensField($p['contractor_email'] ?? '', $userRole, 'email'); ?></p>
            </div>
        </div>
    </div>

    <!-- Description -->
    <?php if ($p['description']): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-7 mb-5">
        <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                <i class="fas fa-file-lines text-slate-500 text-sm"></i>
            </span>
            Deskripsi Proyek
        </h2>
        <p class="text-slate-600 text-sm leading-relaxed"><?php echo nl2br(e($p['description'])); ?></p>
    </div>
    <?php endif; ?>

    <div class="text-center mt-8 mb-4">
        <a href="<?php echo url('pages/projects.php'); ?>"
           class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i> Back to Find Projects
        </a>
    </div>

</div>
</main>

<?php $footerVariant = 'light'; require __DIR__ . '/../includes/footer.php'; ?>
<?php require __DIR__ . '/../includes/scripts.php'; ?>
</body>
</html>
