<?php
/**
 * Partial: Script penutup halaman (mobile menu toggle + Swiper init bila perlu).
 * Set $useSwiper = true sebelum include header.php jika halaman memakai Swiper.
 */
?>
<?php if (!empty($useSwiper)): ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (!empty($useSwiper)): ?>
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            autoHeight: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            effect: "slide"
        });
        <?php endif; ?>

        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileIcon = mobileBtn ? mobileBtn.querySelector('i') : null;

        if (mobileBtn && mobileMenu && mobileIcon) {
            mobileBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');

                if (mobileMenu.classList.contains('hidden')) {
                    mobileIcon.classList.remove('fa-times');
                    mobileIcon.classList.add('fa-bars');
                } else {
                    mobileIcon.classList.remove('fa-bars');
                    mobileIcon.classList.add('fa-times');
                }
            });
        }
    });
</script>
