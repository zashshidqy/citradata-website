<?php
/**
 * Partial: Hero header dengan background image, dipakai di semua halaman.
 */
?>
<header class="relative w-full aspect-[4/3] sm:aspect-[16/9] lg:aspect-[21/9] min-h-[400px] max-h-[500px] flex items-center justify-center overflow-hidden bg-slate-900">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo asset('assets/images/hero.png'); ?>" alt="Construction Hero" class="w-full h-full object-cover object-center" />
        <div class="absolute inset-0 bg-gradient-to-r from-brandBlue/90 to-brandBlue/50 mix-blend-multiply"></div>
    </div>
    <div class="relative z-10 max-w-5xl mx-auto px-4 md:px-6 text-center">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-4 tracking-tight drop-shadow-2xl">
            Discover accurate and detailed <br class="hidden sm:block"> construction data
        </h1>
        <p class="text-sm sm:text-base md:text-xl text-blue-50/90 font-medium max-w-2xl mx-auto tracking-wide drop-shadow-md">
            to identify potential market opportunities <br class="hidden sm:block"> and strengthen business connections
        </p>
    </div>
</header>
