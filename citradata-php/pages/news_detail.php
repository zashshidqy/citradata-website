<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);
$news = null;

if ($id > 0) {
    try {
        $pdo  = getDbConnection();
        $stmt = $pdo->prepare('SELECT * FROM latest_news WHERE id = :id AND is_active = 1 LIMIT 1');
        $stmt->execute([':id' => $id]);
        $news = $stmt->fetch();
    } catch (Exception $e) {}
}

if (!$news) {
    header('Location: ' . url('index.php'));
    exit;
}

$pageTitle = e($news['title']) . ' – Citradata';
$useSwiper = false;
require __DIR__ . '/../includes/head.php';
?>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">
<?php require __DIR__ . '/../includes/nav.php'; ?>

<main class="flex-grow py-16 px-4">
    <div class="max-w-3xl mx-auto">
        <a href="<?php echo url('index.php#latest-news'); ?>" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-brandBlue mb-8 transition-colors font-medium">
            <i class="fas fa-arrow-left text-xs"></i> Back to News
        </a>

        <article class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-slate-100">
            <?php if (!empty($news['image_url'])): ?>
            <div class="rounded-2xl overflow-hidden mb-8 aspect-video">
                <img src="<?php echo e($news['image_url']); ?>" alt="<?php echo e($news['title']); ?>" class="w-full h-full object-cover">
            </div>
            <?php endif; ?>

            <p class="text-slate-400 text-xs mb-3"><?php echo date('d F Y', strtotime($news['created_at'])); ?></p>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 leading-tight mb-6"><?php echo e($news['title']); ?></h1>
            <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm md:text-base">
                <?php echo $news['content'] ?? nl2br(e($news['summary'] ?? '')); ?>
            </div>
        </article>
    </div>
</main>

<?php $footerVariant = 'light'; require __DIR__ . '/../includes/footer.php'; ?>
<?php require __DIR__ . '/../includes/scripts.php'; ?>
</body>
</html>
