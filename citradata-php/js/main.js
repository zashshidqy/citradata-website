document.addEventListener("DOMContentLoaded", function() {
    // Hero Image Slider
    if(document.querySelector('.mySwiper')) {
        new Swiper(".mySwiper", {
            loop: true,
            effect: "fade", 
            fadeEffect: { crossFade: true },
            autoplay: { delay: 4000, disableOnInteraction: false },
            pagination: { el: ".swiper-pagination", clickable: true },
        });
    }

    // Navigation scroll effect
    const nav = document.querySelector('nav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            nav.classList.add('shadow-sm', 'border-slate-200/50');
            nav.classList.remove('border-transparent');
        } else {
            nav.classList.remove('shadow-sm', 'border-slate-200/50');
            nav.classList.add('border-transparent');
        }
    });
});