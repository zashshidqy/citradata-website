<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$pageTitle    = 'Citradata - Project Information Services';
$useSwiper    = true;
$useTestiSwiper = true;

$newsList     = getActiveNews(3);
$testimonials = getActiveTestimonials(8);
$clientLogos  = getLogos('client');
$colabLogos   = getLogos('collaboration');

require __DIR__ . '/includes/head.php';
?>
<body class="bg-[#F8FAFC] text-slate-800 antialiased flex flex-col min-h-screen">

<?php require __DIR__ . '/includes/nav.php'; ?>

<main class="flex-grow">
    <?php require __DIR__ . '/includes/hero.php'; ?>

    <!-- Tagline Strip -->
    <section class="bg-brandRed relative z-20 shadow-lg overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 md:py-6 text-center">
            <p class="text-white font-medium text-xs sm:text-sm md:text-base tracking-wide leading-relaxed">
                Citradata is a pioneer in delivering comprehensive construction project data information, with over two decades of experience
            </p>
        </div>
    </section>

    <!-- Slide Banner + Collaboration -->
    <section class="py-12 md:py-24">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="bg-white rounded-[2rem] md:rounded-[3rem] p-5 md:p-10 lg:p-12 shadow-xl border border-slate-100 flex flex-col gap-8 md:gap-12 hover:shadow-2xl transition-shadow duration-500">
                <div class="w-full rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden shadow-sm relative bg-[#F8FAFC] border border-slate-100">
                    <div class="swiper mySwiper w-full">
                        <div class="swiper-wrapper items-center">
                            <div class="swiper-slide"><img src="<?php echo asset('assets/images/1.png'); ?>" alt="Collaboration" class="w-full h-auto max-h-[600px] object-contain" loading="lazy"></div>
                            <div class="swiper-slide"><img src="<?php echo asset('assets/images/2.png'); ?>" alt="Meeting"       class="w-full h-auto max-h-[600px] object-contain" loading="lazy"></div>
                            <div class="swiper-slide"><img src="<?php echo asset('assets/images/3.png'); ?>" alt="Construction"  class="w-full h-auto max-h-[600px] object-contain" loading="lazy"></div>
                            <div class="swiper-slide"><img src="<?php echo asset('assets/images/4.png'); ?>" alt="Project"       class="w-full h-auto max-h-[600px] object-contain" loading="lazy"></div>
                            <div class="swiper-slide"><img src="<?php echo asset('assets/images/5.png'); ?>" alt="Planning"      class="w-full h-auto max-h-[600px] object-contain" loading="lazy"></div>
                            <div class="swiper-slide"><img src="<?php echo asset('assets/images/6.png'); ?>" alt="Planning-2"    class="w-full h-auto max-h-[600px] object-contain" loading="lazy"></div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products -->
    <section class="pb-8 pt-4">
        <div class="max-w-7xl mx-auto px-4 md:px-6 grid md:grid-cols-2 gap-6 md:gap-10">
            <article class="bg-white rounded-[1.5rem] md:rounded-[2rem] overflow-hidden border border-slate-100 group flex flex-col h-full hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 md:hover:-translate-y-2">
                <div class="h-48 md:h-64 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=800" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                    <div class="absolute inset-0 bg-brandRed/80 mix-blend-multiply transition-opacity group-hover:opacity-60"></div>
                    <h2 class="absolute inset-0 flex items-center justify-center text-2xl md:text-3xl font-bold text-white tracking-tight text-center px-4">Construction Data</h2>
                </div>
                <div class="p-6 md:p-10 flex flex-col flex-grow justify-between">
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-6 md:mb-8 font-medium">The Citradata online platform provides real-time access to the latest construction project data, helping you to identify the right opportunities to grow your sales pipeline.</p>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-6 border-t border-slate-100 gap-4 sm:gap-0">
                        <a href="<?php echo url('pages/login.php?trial=1'); ?>" class="px-6 py-2.5 md:px-7 md:py-3 bg-brandBlue text-white text-xs md:text-sm font-semibold rounded-full hover:bg-blue-800 transition-colors text-center w-full sm:w-auto shadow-md">Find Projects</a>
                        <span class="text-[10px] md:text-xs text-slate-400 font-medium sm:text-right max-w-[120px]">Get preview access by a free trial</span>
                    </div>
                </div>
            </article>

            <article class="bg-white rounded-[1.5rem] md:rounded-[2rem] overflow-hidden border border-slate-100 group flex flex-col h-full hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 md:hover:-translate-y-2">
                <div class="h-48 md:h-64 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                    <div class="absolute inset-0 bg-brandBlue/80 mix-blend-multiply transition-opacity group-hover:opacity-60"></div>
                    <h2 class="absolute inset-0 flex items-center justify-center text-2xl md:text-3xl font-bold text-white tracking-tight text-center px-4">Market Insights</h2>
                </div>
                <div class="p-6 md:p-10 flex flex-col flex-grow justify-between">
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-6 md:mb-8 font-medium">The core services comprise the regular market analysis reports and customised market research with a specific focus on the construction market sector.</p>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-6 border-t border-slate-100 gap-4 sm:gap-0">
                        <a href="<?php echo url('pages/product.php'); ?>" class="px-6 py-2.5 md:px-7 md:py-3 bg-slate-100 text-brandBlue text-xs md:text-sm font-semibold rounded-full hover:bg-slate-200 transition-colors text-center w-full sm:w-auto shadow-sm">Read More</a>
                        <span class="text-[10px] md:text-xs text-slate-400 font-medium sm:text-right max-w-[120px]">Preview of Monthly Project Summary</span>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- Latest News -->
    <section class="py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Latest News</h2>
                <a href="<?php echo url('pages/news.php'); ?>" class="text-brandBlue text-sm font-semibold hover:underline flex items-center gap-1.5">
                    View All <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <?php if (!empty($newsList)): ?>
            <div class="grid md:grid-cols-<?php echo min(count($newsList), 3); ?> gap-6">
                <?php foreach ($newsList as $news): ?>
                <article class="bg-white rounded-[1.5rem] overflow-hidden border border-slate-100 group flex flex-col hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
                    <?php if (!empty($news['image_url'])): ?>
                    <div class="h-40 overflow-hidden relative">
                        <img src="<?php echo e($news['image_url']); ?>" alt="<?php echo e($news['title']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        <div class="absolute inset-0 bg-slate-800/40 group-hover:bg-slate-800/30 transition-colors"></div>
                    </div>
                    <?php else: ?>
                    <div class="h-1.5 bg-gradient-to-r from-brandBlue to-brandRed"></div>
                    <?php endif; ?>
                    <div class="p-6 flex flex-col flex-grow">
                        <p class="text-[11px] text-slate-400 mb-2"><?php echo date('d M Y', strtotime($news['created_at'])); ?></p>
                        <h3 class="font-bold text-slate-900 text-base mb-3 leading-snug line-clamp-2"><?php echo e($news['title']); ?></h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-4 flex-grow line-clamp-3"><?php echo e($news['summary'] ?? ''); ?></p>
                        <a href="<?php echo url('pages/news_detail.php?id=' . $news['id']); ?>" class="inline-flex items-center gap-1 text-brandBlue text-xs font-semibold hover:gap-2 transition-all">Read More <i class="fas fa-arrow-right text-[10px]"></i></a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-2xl p-10 text-center border border-slate-100">
                <p class="text-slate-400 text-sm">Belum ada berita. Tambah melalui <a href="<?php echo url('admin/news.php'); ?>" class="text-brandBlue hover:underline">Admin Panel</a>.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Testimonials — Auto Slider + Popup -->
    <section class="py-12 md:py-16 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">What Our Clients Say</h2>
                <?php if (!empty($testimonials)): ?>
                <a href="<?php echo url('pages/testimonials.php'); ?>" class="text-brandBlue text-sm font-semibold hover:underline flex items-center gap-1.5">
                    View All <i class="fas fa-arrow-right text-xs"></i>
                </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($testimonials)): ?>
            <!-- Swiper testimonial -->
            <div class="swiper testimonialSwiper overflow-hidden">
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $t): ?>
                    <div class="swiper-slide h-auto">
                        <div class="testimonial-card bg-white rounded-2xl p-7 border border-slate-100 shadow-sm
                                    hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer h-full flex flex-col"
                             data-name="<?php echo e($t['author_name']); ?>"
                             data-role="<?php echo e($t['author_role'] ?? ''); ?>"
                             data-company="<?php echo e($t['company'] ?? ''); ?>"
                             data-rating="<?php echo (int)$t['rating']; ?>"
                             data-content="<?php echo e($t['content']); ?>">
                            <div class="flex gap-1 mb-4">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star text-xs <?php echo $i <= (int)$t['rating'] ? 'text-yellow-400' : 'text-slate-200'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed italic flex-grow mb-5 line-clamp-4">
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
                                        <?php if (!empty($t['company'])): ?>&mdash; <?php echo e($t['company']); ?><?php endif; ?>
                                    </p>
                                </div>
                                <i class="fas fa-expand-alt text-slate-200 ml-auto text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-2xl p-10 text-center border border-slate-100">
                <p class="text-slate-400 text-sm">Belum ada testimonial. Tambah melalui <a href="<?php echo url('admin/testimonials.php'); ?>" class="text-brandBlue hover:underline">Admin Panel</a>.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Testimonial Popup Modal -->
    <div id="testimonial-modal" class="fixed inset-0 z-[100] flex items-center justify-center px-4 hidden">
        <div id="modal-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8 md:p-10 z-10">
            <button id="modal-close" class="absolute top-4 right-4 w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
            <div id="modal-stars" class="flex gap-1 mb-5"></div>
            <blockquote class="relative mb-6">
                <i class="fas fa-quote-left text-brandBlue/20 text-4xl absolute -top-2 -left-1"></i>
                <p id="modal-content" class="text-slate-700 text-base leading-relaxed pl-8 italic"></p>
            </blockquote>
            <div class="flex items-center gap-4 pt-5 border-t border-slate-100">
                <div id="modal-avatar" class="w-12 h-12 rounded-full bg-brandBlue/10 flex items-center justify-center text-brandBlue font-bold text-lg shrink-0"></div>
                <div>
                    <p id="modal-name"    class="font-bold text-slate-900 text-base"></p>
                    <p id="modal-subrole" class="text-slate-400 text-sm mt-0.5"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Collaborations & Valuable Clients -->
    <section class="py-16 md:py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            
            <!-- Collaborations (Static) -->
            <div class="mb-16">
                <h3 class="text-center font-bold text-slate-400 uppercase tracking-[0.25em] text-xs mb-10">Collaborations</h3>
                <?php if (!empty($colabLogos)): ?>
                <div class="flex items-center justify-center gap-16 flex-wrap">
                    <?php foreach ($colabLogos as $logo): ?>
                    <div class="flex items-center justify-center">
                        <?php if (!empty($logo['website_url'])): ?>
                        <a href="<?php echo e($logo['website_url']); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo e($logo['name']); ?>" class="block transition-all duration-300 hover:scale-105">
                            <img src="<?php echo asset($logo['logo_path']); ?>" alt="<?php echo e($logo['name']); ?>" class="h-12 object-contain max-w-[140px] opacity-50 hover:opacity-80 transition-opacity duration-300">
                        </a>
                        <?php else: ?>
                        <img src="<?php echo asset($logo['logo_path']); ?>" alt="<?php echo e($logo['name']); ?>" class="h-12 object-contain max-w-[140px] opacity-50">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-center text-slate-400 text-xs">Belum ada logo kolaborasi.</p>
                <?php endif; ?>
            </div>

            <!-- Valuable Clients (Running Slider) -->
            <div>
                <h3 class="text-center font-bold text-slate-400 uppercase tracking-[0.25em] text-xs mb-10">Valuable Clients</h3>
                <?php if (!empty($clientLogos)): ?>
                <div class="client-slider-container overflow-hidden">
                    <div class="client-slider flex items-center gap-20">
                        <?php 
                        // Duplicate logos for seamless infinite scroll
                        $duplicatedClientLogos = array_merge($clientLogos, $clientLogos, $clientLogos);
                        foreach ($duplicatedClientLogos as $logo): 
                        ?>
                        <div class="flex-shrink-0 flex items-center justify-center">
                            <?php if (!empty($logo['website_url'])): ?>
                            <a href="<?php echo e($logo['website_url']); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo e($logo['name']); ?>" class="block transition-all duration-300 hover:scale-105">
                                <img src="<?php echo asset($logo['logo_path']); ?>" alt="<?php echo e($logo['name']); ?>" class="h-10 object-contain max-w-[120px] opacity-50 hover:opacity-80 transition-opacity duration-300">
                            </a>
                            <?php else: ?>
                            <img src="<?php echo asset($logo['logo_path']); ?>" alt="<?php echo e($logo['name']); ?>" class="h-10 object-contain max-w-[120px] opacity-50">
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else: ?>
                <p class="text-center text-slate-400 text-xs">Belum ada logo klien.</p>
                <?php endif; ?>
            </div>
            
        </div>
    </section>

</main>

<?php $footerVariant = 'dark'; require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>

<!-- Testimonial Swiper + Popup Script -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Testimonial auto slider
    new Swiper('.testimonialSwiper', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 24,
        autoplay: { delay: 4500, disableOnInteraction: false, pauseOnMouseEnter: true },
        breakpoints: {
            640:  { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });

    // Popup modal logic
    const modal    = document.getElementById('testimonial-modal');
    const backdrop = document.getElementById('modal-backdrop');
    const btnClose = document.getElementById('modal-close');

    function openModal(card) {
        const name    = card.dataset.name;
        const role    = card.dataset.role;
        const company = card.dataset.company;
        const rating  = parseInt(card.dataset.rating) || 5;
        const content = card.dataset.content;

        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="fas fa-star text-sm ${i <= rating ? 'text-yellow-400' : 'text-slate-200'}"></i>`;
        }
        document.getElementById('modal-stars').innerHTML   = stars;
        document.getElementById('modal-content').textContent = content;
        document.getElementById('modal-avatar').textContent  = name.charAt(0).toUpperCase();
        document.getElementById('modal-name').textContent    = name;

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

    document.querySelectorAll('.testimonial-card').forEach(function (card) {
        card.addEventListener('click', function () { openModal(this); });
    });

    if (btnClose)  btnClose.addEventListener('click', closeModal);
    if (backdrop)  backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
});
</script>


</body>
</html>
