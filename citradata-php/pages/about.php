<?php
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'About Us - Citradata Project Information Services';
$useSwiper = false;
$extraHeadStyle = ''; // grid background sudah ada di head.php
require __DIR__ . '/../includes/head.php';
?>
<body class="bg-slate-50 text-foreground antialiased flex flex-col min-h-screen selection:bg-brandBlue/20 selection:text-brandBlue">

    <?php require __DIR__ . '/../includes/nav.php'; ?>

    <main class="flex-grow relative overflow-hidden">

        <?php require __DIR__ . '/../includes/hero.php'; ?>

        <div class="absolute inset-0 z-0 pointer-events-none flex justify-center mt-[400px]">
            <div class="absolute inset-0 bg-grid-slate-100 mask-radial"></div>
        </div>

        <div class="relative z-10 pt-16 md:pt-24 px-4 text-center">
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-600 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-brandBlue mr-2"></span>
                    Company Profile
                </div>

                <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-slate-900 leading-tight">
                    Get to know more <br class="hidden md:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-600 to-slate-400">About Us.</span>
                </h1>
            </div>
        </div>

        <section class="py-12 md:py-20 relative z-10">
            <div class="max-w-4xl mx-auto px-4 md:px-6 space-y-12">

                <article class="bg-white rounded-[2rem] p-8 md:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
                    <div class="flex items-center gap-5 mb-8 pb-8 border-b border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-700 shrink-0 border border-slate-200">
                            <i class="fas fa-info-circle text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">About Us</h2>
                            <p class="text-sm text-slate-500 font-medium mt-1">Established 1988</p>
                        </div>
                    </div>

                    <div class="space-y-6 text-slate-600 text-base md:text-lg leading-relaxed font-medium">
                        <p>Citradata is a pioneer in delivering comprehensive construction project data information. The company has an established history of providing data solutions, with a proven track record that spans more than two decades since its establishment in 1988.</p>

                        <p>The project data is obtained through a research methodology process that focuses on accurate, timely and detailed data quality through confirmation and verification with all construction players.</p>

                        <p>We are committed to providing our clients with the highest quality and quantity of valuable project data. We are proactively building partnerships and networking with the construction community, and regularly presenting market insights analysis to facilitate navigation of the construction market.</p>

                        <p>We are conducting prudent research processes, supported by access to a wide range of relevant information sources and the expertise of a dedicated field survey team</p>
                    </div>
                </article>

                <article class="bg-white rounded-[2rem] p-8 md:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
                    <div class="flex items-center gap-5 mb-8 pb-8 border-b border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-brandRed shrink-0">
                            <i class="fas fa-bullseye text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Our Mission</h2>
                            <p class="text-sm text-slate-500 font-medium mt-1">Quality, Partnership & Expansion</p>
                        </div>
                    </div>

                    <div class="space-y-6 text-slate-600 text-base md:text-lg leading-relaxed font-medium">
                        <p>We are committed to providing our clients with the highest quality and quantity of valuable project data. We are proactively building partnerships and networking with the construction community, and regularly presenting market insights analysis to facilitate navigation of the construction market.</p>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 md:p-8 my-8 relative">
                            <p class="text-slate-800 font-semibold italic">
                                "We are conducting prudent research processes, supported by access to a wide range of relevant information sources and the expertise of a dedicated field survey team."
                            </p>
                        </div>

                        <p>We are committed to providing accurate, up-to-date and reliable construction data to all key players involved in construction, with a view to expanding business opportunities.</p>

                        <p>We believe that we can make a valuable contribution to the construction community by establishing and maintaining business connections with a wide range of professionals, Including developers, consultants, contractors, manufacturers and distributors of building materials.</p>
                    </div>
                </article>

                <article class="bg-white rounded-[2rem] p-8 md:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
                    <div class="flex items-center gap-5 mb-8 pb-8 border-b border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-brandBlue shrink-0">
                            <i class="fas fa-eye text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Our Vision</h2>
                            <p class="text-sm text-slate-500 font-medium mt-1">The Primary Reference Source</p>
                        </div>
                    </div>

                    <div class="text-slate-600 text-base md:text-lg leading-relaxed font-medium">
                        <p class="text-xl md:text-2xl text-slate-800 font-light leading-snug">
                            "To establish Citradata as the <span class="font-bold text-brandBlue">leading trusted and reputable</span> project construction data information provider, thereby strengthening its position as the primary reference source for all construction professionals."
                        </p>
                    </div>
                </article>

            </div>
        </section>
    </main>

    <?php $footerVariant = 'light'; require __DIR__ . '/../includes/footer.php'; ?>

    <?php require __DIR__ . '/../includes/scripts.php'; ?>
</body>
</html>
