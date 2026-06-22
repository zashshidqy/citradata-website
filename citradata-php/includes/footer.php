<?php
/**
 * Partial: Footer.
 * Set $footerVariant sebelum include: 'dark' | 'light'
 */

if (!isset($footerVariant)) {
    $footerVariant = 'dark';
}

if ($footerVariant === 'light') {
    $footerBg          = 'bg-white border-t border-border';
    $logoClass         = 'h-7 md:h-8 object-contain mb-6 grayscale opacity-80';
    $sectionLabelClass = 'text-[10px] md:text-[11px] text-slate-500 uppercase tracking-[0.2em] font-bold mb-4';
    $descClass         = 'text-sm leading-relaxed text-mutedForeground max-w-sm mb-6';
    $socialIconClass   = 'w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-brandBlue transition-colors';
    $companyNameClass  = 'text-slate-900 block text-lg mb-4 font-bold tracking-tight';
    $addressClass      = 'text-mutedForeground leading-relaxed';
    $contactTextClass  = 'text-slate-600';
    $contactIconClass  = 'text-slate-400';
    $websiteClass      = 'text-brandBlue break-all';
    $bottomBorderClass = 'border-t border-slate-100 text-slate-400';
    $containerPad      = 'px-4 md:px-8';
    $phoneColor        = $contactIconClass;
    $waColor           = $contactIconClass;
    $emailColor        = $contactIconClass;
    $globeColor        = $contactIconClass;
} else {
    $footerBg          = 'bg-slate-900 text-slate-300';
    $logoClass         = 'h-8 md:h-10 object-contain mb-4 filter brightness-0 invert opacity-80';
    $sectionLabelClass = 'text-[9px] md:text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-6 md:mb-8';
    $descClass         = 'text-xs md:text-sm leading-relaxed text-slate-400 font-medium max-w-sm mb-6 md:mb-8';
    $socialIconClass   = 'w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-800 flex items-center justify-center text-white hover:bg-brandBlue transition-all';
    $companyNameClass  = 'text-white block text-base md:text-lg mb-4 md:mb-6 font-bold tracking-tight';
    $addressClass      = 'text-slate-400';
    $contactTextClass  = 'text-slate-300';
    $contactIconClass  = 'text-slate-500';
    $websiteClass      = 'text-slate-300 break-all';
    $bottomBorderClass = 'border-t border-slate-800 text-slate-500';
    $containerPad      = 'px-4 md:px-6';
    $phoneColor        = 'text-brandRed';
    $waColor           = 'text-green-500';
    $emailColor        = 'text-brandBlue';
    $globeColor        = 'text-slate-500';
}
?>
<footer class="<?php echo $footerBg; ?> pt-16 pb-8 md:pt-20 md:pb-10">
    <div class="max-w-7xl mx-auto <?php echo $containerPad; ?> grid md:grid-cols-2 gap-12 lg:gap-24">
        <div>
            <img src="<?php echo asset('assets/images/citradata-logo.png'); ?>" alt="Citradata" class="<?php echo $logoClass; ?>">
            <div class="<?php echo $sectionLabelClass; ?>">Project Information Services</div>
            <p class="<?php echo $descClass; ?>">Citradata is a pioneer in delivering comprehensive construction project data information, with over two decades of experience.</p>
            <div class="flex space-x-3">
                <a href="https://www.linkedin.com/company/pt-citradata-indonusa" target="_blank" rel="noopener noreferrer" class="<?php echo $socialIconClass; ?>" title="LinkedIn">
                    <i class="fab fa-linkedin-in text-sm md:text-base"></i>
                </a>
                <a href="https://www.instagram.com/citradata/" target="_blank" rel="noopener noreferrer" class="<?php echo $socialIconClass; ?>" title="Instagram">
                    <i class="fab fa-instagram text-sm md:text-base"></i>
                </a>
                <a href="https://www.facebook.com/CitradataKonstruksi" target="_blank" rel="noopener noreferrer" class="<?php echo $socialIconClass; ?>" title="Facebook">
                    <i class="fab fa-facebook-f text-sm md:text-base"></i>
                </a>
            </div>
        </div>

        <div class="text-xs md:text-sm font-medium space-y-4">
            <strong class="<?php echo $companyNameClass; ?>">PT Citradata Indonusa</strong>
            <?php if ($footerVariant === 'light'): ?>
                <p class="<?php echo $addressClass; ?>">Taman Pegangsaan Indah Blok T/No. 26,<br>Jalan Pegangsaan Dua, Kelapa Gading, Jakarta 14250</p>
            <?php else: ?>
                <p class="<?php echo $addressClass; ?>">Taman Pegangsaan Indah Blok T/No. 26,</p>
                <p class="<?php echo $addressClass; ?>">Jalan Pegangsaan Dua, Kelapa Gading, Jakarta 14250</p>
            <?php endif; ?>

            <div class="pt-4 space-y-3">
                <p class="flex items-center <?php echo $contactTextClass; ?>">
                    <i class="fas fa-phone w-6 <?php echo $phoneColor; ?>"></i>
                    021 460 6509, 460 6518
                </p>

                <!-- WhatsApp direct link chat -->
                <p class="flex items-center <?php echo $contactTextClass; ?>">
                    <i class="fab fa-whatsapp w-6 <?php echo $waColor; ?>"></i>
                    <a href="https://wa.me/6287816109958" target="_blank" rel="noopener noreferrer"
                       class="hover:underline underline-offset-2">
                        0878 1610 9958
                    </a>
                    <span class="text-slate-400 ml-1 text-xs">(WhatsApp Only)</span>
                </p>

                 <p class="flex items-center <?php echo $contactTextClass; ?>">
                    <i class="fas fa-envelope w-6 <?php echo $emailColor; ?>"></i>
                    <a href="mailto:citradata.research@gmail.com" class="hover:underline underline-offset-2">
                        citradata.research@gmail.com
                    </a>
                </p>

                <!-- Email utama -->
                <p class="flex items-center <?php echo $contactTextClass; ?>">
                    <i class="fas fa-envelope w-6 <?php echo $emailColor; ?>"></i>
                    <a href="mailto:citra_jkt@citradataconstruction.com" class="hover:underline underline-offset-2">
                        citra_jkt@citradataconstruction.com
                    </a>
                </p>

                <!-- Email tambahan (item 10) -->

                <p class="flex items-center <?php echo $websiteClass; ?>">
                    <i class="fas fa-globe w-6 <?php echo $globeColor; ?>"></i>
                    <a href="https://www.citradataconstruction.com" target="_blank" rel="noopener noreferrer"
                       class="hover:underline underline-offset-2">
                        www.citradataconstruction.com
                    </a>
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto <?php echo $containerPad; ?> mt-12 md:mt-16 pt-6 md:pt-8 <?php echo $bottomBorderClass; ?> text-center flex flex-col md:flex-row justify-between items-center text-[10px] md:text-xs font-medium gap-4">
        <p>&copy; <?php echo date('Y'); ?> PT Citradata Indonusa. All rights reserved.</p>
        <p>
            <a href="<?php echo url('admin/index.php'); ?>" class="opacity-30 hover:opacity-60 transition-opacity">Admin</a>
        </p>
    </div>
</footer>
