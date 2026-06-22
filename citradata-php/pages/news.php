<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

// Pagination
$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 9;
$offset  = ($page - 1) * $perPage;

try {
    $pdo   = getDbConnection();
    $total = (int)$pdo->query('SELECT COUNT(*) FROM latest_news WHERE is_active = 1')->fetchColumn();
    $stmt  = $pdo->prepare(
        'SELECT * FROM latest_news WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC LIMIT :lim OFFSET :off'
    );
    $stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset,  PDO::PARAM_INT);
    $stmt->execute();
    $newsList = $stmt->fetchAll();
} catch (Exception $e) {
    $newsList = [];
    $total    = 0;
}

$pages     = max(1, ceil($total / $perPage));
$pageTitle = 'Latest News – Citradata';
$useSwiper = false;
require __DIR__ . '/../includes/head.php';
?>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">
<?php require __DIR__ . '/../includes/nav.php'; ?>

<main class="flex-grow">
    <?php require __DIR__ . '/../includes/hero.php'; ?>

    <section class="py-14 md:py-20">
        <div class="max-w-7xl mx-auto px-4 md:px-6">

            <div class="flex items-center justify-between mb-10">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Latest News</h1>
                    <p class="text-slate-500 text-sm mt-1"><?php echo $total; ?> article<?php echo $total !== 1 ? 's' : ''; ?></p>
                </div>
            </div>

            <?php if (!empty($newsList)): ?>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <?php foreach ($newsList as $news): ?>
                <article class="bg-white rounded-2xl overflow-hidden border border-slate-100 group flex flex-col hover:shadow-xl transition-all duration-400 hover:-translate-y-1">
                    <?php if (!empty($news['image_url'])): ?>
                    <div class="h-44 overflow-hidden relative">
                        <img src="<?php echo e($news['image_url']); ?>"
                             alt="<?php echo e($news['title']); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent"></div>
                    </div>
                    <?php else: ?>
                    <div class="h-2 bg-gradient-to-r from-brandBlue to-brandRed"></div>
                    <?php endif; ?>

                    <div class="p-6 flex flex-col flex-grow">
                        <p class="text-[11px] text-slate-400 mb-2 font-medium"><?php echo date('d F Y', strtotime($news['created_at'])); ?></p>
                        <h2 class="font-bold text-slate-900 text-base leading-snug mb-3 line-clamp-2 group-hover:text-brandBlue transition-colors">
                            <?php echo e($news['title']); ?>
                        </h2>
                        <p class="text-slate-500 text-sm leading-relaxed flex-grow line-clamp-3 mb-5">
                            <?php echo e($news['summary'] ?? ''); ?>
                        </p>
                        <a href="<?php echo url('pages/news_detail.php?id=' . $news['id']); ?>"
                           class="inline-flex items-center gap-1.5 text-brandBlue text-xs font-semibold hover:gap-3 transition-all duration-200">
                            Read More <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pages > 1): ?>
            <div class="flex justify-center gap-2 mt-12">
                <?php for ($p = 1; $p <= $pages; $p++): ?>
                <a href="?page=<?php echo $p; ?>"
                   class="w-9 h-9 flex items-center justify-center rounded-xl text-sm font-semibold transition-colors
                       <?php echo $p === $page ? 'bg-brandBlue text-white shadow' : 'bg-white border border-slate-200 text-slate-600 hover:border-brandBlue hover:text-brandBlue'; ?>">
                    <?php echo $p; ?>
                </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <div class="bg-white rounded-2xl p-16 text-center border border-slate-100">
                <i class="fas fa-newspaper text-4xl text-slate-200 mb-4 block"></i>
                <p class="text-slate-400 text-sm">Belum ada berita yang dipublikasikan.</p>
            </div>
            <?php endif; ?>

        </div>
    </section>
</main>

<?php $footerVariant = 'light'; require __DIR__ . '/../includes/footer.php'; ?>
<?php require __DIR__ . '/../includes/scripts.php'; ?>
</body>
</html>
