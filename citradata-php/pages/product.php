<?php
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Product - Citradata Project Information Services';
$useSwiper = false;
require __DIR__ . '/../includes/head.php';
?>
<body class="bg-[#F8FAFC] text-slate-800 antialiased flex flex-col min-h-screen selection:bg-brandBlue/20 selection:text-brandBlue">

    <?php require __DIR__ . '/../includes/nav.php'; ?>

    <main class="flex-grow">

        <?php require __DIR__ . '/../includes/hero.php'; ?>

        <section class="py-16 md:py-24 relative z-10">
            <div class="max-w-4xl mx-auto px-4 md:px-6 space-y-12">

                <article class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                    <!-- Header Image -->
                    <div class="h-32 md:h-44 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=800&q=80" 
                             alt="Construction Data" 
                             class="w-full h-full object-cover opacity-75">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-900/70 to-red-900/10"></div>
                        <div class="absolute bottom-4 left-6 md:left-8 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/90 backdrop-blur-sm flex items-center justify-center text-brandRed">
                                <i class="fas fa-building text-lg"></i>
                            </div>
                            <div class="text-white">
                                <h3 class="font-bold text-lg">Construction Data</h3>
                                <p class="text-xs opacity-80">Digital & Printed Project Information</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 md:p-12">
                        <div class="space-y-6 text-slate-600 text-base md:text-lg leading-relaxed font-medium">
                            <p class="text-slate-900 font-semibold text-xl leading-snug">
                                The core service provided by Citradata Construction Data consists of the provision of construction project data in both digital and printed formats.
                            </p>

                            <p>Citradata digital version is an online platform that provides unlimited real-time access to the latest construction project data and progress updates published on the Citradata website.</p>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 md:p-8 my-8 relative">
                                <p class="text-slate-900 font-bold mb-3 text-lg">Citradata Digital Editions offer significant advantages over traditional print versions.</p>
                                <p class="text-sm md:text-base text-slate-600">The key benefits include convenience and instant accessibility, allowing users to retrieve the latest project information at any time and from any location with an internet connection. The digital edition feature offers substantial advantages by transforming static content into interactive content.</p>
                            </div>

                            <p>Citradata members are able to undertake efficient searches for projects according to their specific requirements, identify key individuals for further follow-up, and download Excel sheets that facilitate more comprehensive data analysis.</p>

                            <p>Citradata Printed Edition is a hardcopy magazine published on a monthly basis. The content and project information featured in the magazine is the same as in the Citradata Digital Edition. In accordance with the increasing popularity of digital editions, Citradata is encouraging its members to transition from print to digital.</p>

                            <blockquote class="italic text-slate-500 border-l-4 border-slate-200 pl-5 mt-8">
                                We continue to emphasise the value of digital editions, extending beyond simple convenience to encompass interactive capabilities, immediate access, customised features and cost efficiencies that traditional print cannot match.
                            </blockquote>
                        </div>
                    </div>
                </article>

                <article class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                    <!-- Header Image -->
                    <div class="h-32 md:h-44 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80" 
                             alt="Market Insights" 
                             class="w-full h-full object-cover opacity-75">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/70 to-blue-900/10"></div>
                        <div class="absolute bottom-4 left-6 md:left-8 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/90 backdrop-blur-sm flex items-center justify-center text-brandBlue">
                                <i class="fas fa-chart-pie text-lg"></i>
                            </div>
                            <div class="text-white">
                                <h3 class="font-bold text-lg">Market Insights</h3>
                                <p class="text-xs opacity-80">Analysis Reports & Customised Research</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 md:p-12">
                        <div class="space-y-6 text-slate-600 text-base md:text-lg leading-relaxed font-medium">
                            <p class="text-slate-900 font-semibold text-xl leading-snug">
                                The core services provided by Citradata Market Insights comprise the regular market analysis reports and the provision of customised market research with a specific focus on the construction market sector.
                            </p>

                            <div class="rounded-2xl border border-slate-200 bg-white p-6 my-8 shadow-sm">
                                <h3 class="font-bold text-slate-900 text-lg mb-3 flex items-center gap-3">
                                    <i class="fas fa-file-alt text-brandBlue"></i> Monthly Project Summary
                                </h3>
                                <p class="text-sm md:text-base text-slate-600">Provides a monthly overview of the latest construction projects and valuable market insights, based on construction project data published by Citradata Project Information Services. The report provides an overview and analysis of the latest projects, from design planning to the early stages of construction, offering business opportunities for those involved in construction projects.</p>
                            </div>

                            <p>A comprehensive analysis encompasses a range of economic indicators, alongside the most recent government policies and private sector business expansion, all of which exert a significant influence on the construction sector.</p>

                            <p>The analysis is grounded in a comprehensive overview of seven primary construction project sectors:</p>

                            <div class="flex flex-wrap gap-2 md:gap-3 pt-2">
                                <span class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700">Commersial Building</span>
                                <span class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700">High rest Building</span>
                                <span class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700">Industrial</span>
                                <span class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700">Resindantial</span>
                                <span class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700">Hospitality</span>
                                <span class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700">Hospitals</span>
                                <span class="px-4 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm font-semibold text-slate-500">Other Construction</span>
                            </div>
                        </div>
                    </div>
                </article>

            </div>
        </section>
    </main>

    <?php $footerVariant = 'dark'; require __DIR__ . '/../includes/footer.php'; ?>

    <?php require __DIR__ . '/../includes/scripts.php'; ?>
</body>
</html>
