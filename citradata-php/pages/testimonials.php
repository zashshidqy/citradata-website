<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

try {
    $pdo  = getDbConnection();
    $stmt = $pdo->prepare(
        'SELECT * FROM testimonials WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC'
    );
    $stmt->execute();
    $all = $stmt->fetchAll();
} catch (Exception $e) {
    $all = [];
}

$pageTitle = 'Testimonials – Citradata';
$useSwiper = false;
require __DIR__ . '/../includes/head.php';
?>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">
<?php require __DIR__ . '/../includes/nav.php'; ?>

<main class="flex-grow">
    <?php require __DIR__ . '/../includes/hero.php'; ?>

    <section class="py-14 md:py-20">
        <div class="max-w-7xl mx-auto px-4 md:px-6">

            <div class="mb-10">
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">What Our Clients Say</h1>
                <p class="text-slate-500 text-sm mt-1"><?php echo count($all); ?> testimonial<?php echo count($all) !== 1 ? 's' : ''; ?></p>
            </div>

            <?php if (!empty($all)): ?>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($all as $t): ?>
                <!-- Card — klik untuk popup -->
                <div class="testimonial-card bg-white rounded-2xl p-7 border border-slate-100 shadow-sm
                            hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col"
                     data-id="<?php echo $t['id']; ?>"
                     data-name="<?php echo e($t['author_name']); ?>"
                     data-role="<?php echo e($t['author_role'] ?? ''); ?>"
                     data-company="<?php echo e($t['company'] ?? ''); ?>"
                     data-rating="<?php echo (int)$t['rating']; ?>"
                     data-content="<?php echo e($t['content']); ?>">

                    <!-- Stars -->
                    <div class="flex gap-1 mb-4">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star text-xs <?php echo $i <= (int)$t['rating'] ? 'text-yellow-400' : 'text-slate-200'; ?>"></i>
                        <?php endfor; ?>
                    </div>

                    <p class="text-slate-600 text-sm leading-relaxed italic flex-grow line-clamp-4 mb-5">
                        "<?php echo e($t['content']); ?>"
                    </p>

                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <div class="w-9 h-9 rounded-full bg-brandBlue/10 flex items-center justify-center text-brandBlue font-bold text-sm shrink-0">
                            <?php echo strtoupper(substr($t['author_name'], 0, 1)); ?>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm"><?php echo e($t['author_name']); ?></p>
                            <p class="text-slate-400 text-[11px]">
                                <?php echo e($t['author_role'] ?? ''); ?>
                                <?php if (!empty($t['company'])): ?> &mdash; <?php echo e($t['company']); ?><?php endif; ?>
                            </p>
                        </div>
                        <i class="fas fa-expand-alt text-slate-300 ml-auto text-xs"></i>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php else: ?>
            <div class="bg-white rounded-2xl p-16 text-center border border-slate-100">
                <i class="fas fa-quote-left text-4xl text-slate-200 mb-4 block"></i>
                <p class="text-slate-400 text-sm">Belum ada testimonial yang dipublikasikan.</p>
            </div>
            <?php endif; ?>

        </div>
    </section>
</main>

<!-- ── POPUP MODAL ── -->
<div id="testimonial-modal"
     class="fixed inset-0 z-[100] flex items-center justify-center px-4 hidden"
     aria-modal="true" role="dialog">
    <!-- Backdrop -->
    <div id="modal-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <!-- Card -->
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8 md:p-10 z-10
                animate-in fade-in zoom-in duration-200">

        <!-- Close button -->
        <button id="modal-close"
                class="absolute top-4 right-4 w-9 h-9 flex items-center justify-center rounded-xl
                       bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition-colors">
            <i class="fas fa-times text-sm"></i>
        </button>

        <!-- Stars -->
        <div id="modal-stars" class="flex gap-1 mb-5"></div>

        <!-- Quote -->
        <blockquote class="relative mb-6">
            <i class="fas fa-quote-left text-brandBlue/20 text-4xl absolute -top-2 -left-1"></i>
            <p id="modal-content" class="text-slate-700 text-base leading-relaxed pl-8 italic"></p>
        </blockquote>

        <!-- Author -->
        <div class="flex items-center gap-4 pt-5 border-t border-slate-100">
            <div id="modal-avatar"
                 class="w-12 h-12 rounded-full bg-brandBlue/10 flex items-center justify-center
                        text-brandBlue font-bold text-lg shrink-0">
            </div>
            <div>
                <p id="modal-name"    class="font-bold text-slate-900 text-base"></p>
                <p id="modal-subrole" class="text-slate-400 text-sm mt-0.5"></p>
            </div>
        </div>
    </div>
</div>

<?php $footerVariant = 'light'; require __DIR__ . '/../includes/footer.php'; ?>
<?php require __DIR__ . '/../includes/scripts.php'; ?>

<script>
(function () {
    const modal    = document.getElementById('testimonial-modal');
    const backdrop = document.getElementById('modal-backdrop');
    const btnClose = document.getElementById('modal-close');

    function openModal(card) {
        const name    = card.dataset.name;
        const role    = card.dataset.role;
        const company = card.dataset.company;
        const rating  = parseInt(card.dataset.rating);
        const content = card.dataset.content;

        // Stars
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            starsHtml += `<i class="fas fa-star text-sm ${i <= rating ? 'text-yellow-400' : 'text-slate-200'}"></i>`;
        }
        document.getElementById('modal-stars').innerHTML = starsHtml;

        // Content
        document.getElementById('modal-content').textContent = content;

        // Author
        document.getElementById('modal-avatar').textContent = name.charAt(0).toUpperCase();
        document.getElementById('modal-name').textContent   = name;

        let sub = role;
        if (company) sub += (sub ? ' — ' : '') + company;
        document.getElementById('modal-subrole').textContent = sub;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Attach click to all cards
    document.querySelectorAll('.testimonial-card').forEach(function (card) {
        card.addEventListener('click', function () { openModal(this); });
    });

    btnClose.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
})();
</script>
</body>
</html>
