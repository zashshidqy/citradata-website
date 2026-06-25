<?php
/**
 * Partial: Hero header dengan background image slider, dipakai di semua halaman.
 * Slides dikelola via admin panel (tabel hero_slides).
 */
$heroSlides = getHeroSlides();
$hasSlidesFromDb = !empty($heroSlides);
?>
<header class="relative w-full aspect-[4/3] sm:aspect-[16/9] lg:aspect-[21/9] min-h-[400px] max-h-[500px] flex items-center justify-center overflow-hidden bg-slate-900">

    <?php if ($hasSlidesFromDb): ?>
    <!-- Dynamic Swiper Hero Slider -->
    <div class="absolute inset-0 z-0">
        <div class="swiper heroSwiper w-full h-full">
            <div class="swiper-wrapper">
                <?php foreach ($heroSlides as $slide): ?>
                <div class="swiper-slide">
                    <img src="<?php echo asset($slide['image_path']); ?>"
                         alt="<?php echo e($slide['alt_text'] ?? 'Hero Slide'); ?>"
                         class="w-full h-full object-cover object-center">
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination hero-pagination"></div>
        </div>
    </div>
    <?php else: ?>
    <!-- Fallback static image -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo asset('assets/images/hero.png'); ?>" alt="Construction Hero" class="w-full h-full object-cover object-center" />
        <div class="absolute inset-0 bg-gradient-to-r from-brandBlue/90 to-brandBlue/50 mix-blend-multiply"></div>
    </div>
    <?php endif; ?>

    <div class="relative z-10 max-w-5xl mx-auto px-4 md:px-6 text-center pointer-events-none">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-4 tracking-tight drop-shadow-2xl">
            Discover accurate and detailed <br class="hidden sm:block"> construction data
        </h1>
        <p class="text-sm sm:text-base md:text-xl text-blue-50/90 font-medium max-w-2xl mx-auto tracking-wide drop-shadow-md">
            to identify potential market opportunities <br class="hidden sm:block"> and strengthen business connections
        </p>
    </div>
</header>

<?php if ($hasSlidesFromDb): ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('.heroSwiper')) {
        new Swiper('.heroSwiper', {
            loop: true,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoplay: { delay: 4000, disableOnInteraction: false },
            pagination: { el: '.hero-pagination', clickable: true },
        });
    }
});
</script>
<style>
.heroSwiper { width: 100%; height: 100%; }
.heroSwiper .swiper-slide img { width: 100%; height: 100%; object-fit: cover; object-position: center; }
.hero-pagination { bottom: 12px !important; }
.hero-pagination .swiper-pagination-bullet { background: rgba(255,255,255,0.6) !important; }
.hero-pagination .swiper-pagination-bullet-active { background: #ffffff !important; width: 24px !important; border-radius: 10px !important; }
</style>
<?php endif; ?>
