$(document).ready(function() {


    var testimonialsSwiper = new Swiper('.testimonials-container--original', {
        freeMode: false,
        freeModeMomentum: false,
        slidesPerView: 2.25,
        centeredSlides: true,
        mousewheel: {
            forceToAxis: true,
            invert: true
        },
        breakpoints: {
            1920: {
                slidesPerView: 1.9,
            },
            1750: {
                slidesPerView: 1.8,
            },
            1580: {
                slidesPerView: 1.65,
            },
            1380: {
                slidesPerView: 1.5,
            },
            1200: {
                slidesPerView: 1.95,
            },
            1100: {
                slidesPerView: 1.85,
            },
            900: {
                slidesPerView: 1.75,
            },
            750: {
                slidesPerView: 1,
            },
            // 600: {
            //     slidesPerView: 1.45,
            // },
            // 500: {
            //     slidesPerView: 1.05,
            // },
            600: {
                slidesPerView: 1,
            },
            375: {
                slidesPerView: 1,
            },
        },
        scrollbar: {
            el: '.swiper-scrollbar',
            hide: false,
        },
    })

    testimonialsSwiper.slideTo(1);

    var testimonialsMobileSwiper = new Swiper('.testimonials-container--services', {
        freeMode: false,
        freeModeMomentum: false,
        slidesPerView: 2.25,
        centeredSlides: false,
        mousewheel: {
            forceToAxis: true,
            invert: true
        },
        breakpoints: {
            900: {
                slidesPerView: 2,
            },
            750: {
                slidesPerView: 1.625,
            },
            600: {
                slidesPerView: 1.45,
            },
            500: {
                slidesPerView: 1.25,
            },
            450: {
                slidesPerView: 1.1,
            },
            375: {
                slidesPerView: 1,
            },
        },
        scrollbar: {
            el: '.swiper-scrollbar',
            hide: false,
        },
    })

    testimonialsMobileSwiper.slideTo(0);

    var testimonialsWithImgsSwiper = new Swiper('.testimonials-container--with-imgs', {
        freeMode: false,
        freeModeMomentum: false,
        slidesPerView: 2.25,
        centeredSlides: true,
        mousewheel: {
            forceToAxis: true,
            invert: true
        },
        breakpoints: {
            1920: {
                slidesPerView: 1.4,
            },
            900: {
                slidesPerView: 2,
            },
            750: {
                slidesPerView: 1.625,
            },
            600: {
                slidesPerView: 1.45,
            },
            500: {
                slidesPerView: 1.25,
            },
            450: {
                slidesPerView: 1,
            },
            375: {
                slidesPerView: 1,
            },
        },
        scrollbar: {
            el: '.swiper-scrollbar',
            hide: false,
        },
    })

    testimonialsWithImgsSwiper.slideTo(1);


    // WEB VERSION

    // let sectionContainer = document.querySelector('.testimonials-container');
    //
    // let opinions = document.querySelector('.testimonials__opinions');
    //
    // let containerStartPos = (opinions.scrollWidth - opinions.clientWidth) / 2
    //
    // opinions.scrollLeft = containerStartPos;
    //
    // let posScroll;
    //
    // sectionContainer.addEventListener('mouseenter', (event) => {
    //     let mouseStart = event.clientX;
    //     sectionContainer.addEventListener('mousemove', (e) => {
    //         let x;
    //         let posChangeRange = opinions.scrollWidth - opinions.clientWidth;
    //         x = e.clientX;
    //         posScroll = x - mouseStart + containerStartPos;
    //
    //         if(posScroll < 0) {
    //             posScroll = 0;
    //             mouseStart = x;
    //             containerStartPos = posScroll;
    //         } else if (posScroll > posChangeRange) {
    //             posScroll = posChangeRange;
    //             mouseStart = x;
    //             containerStartPos = posScroll;
    //         }
    //
    //         opinions.scrollLeft = posScroll;
    //         innerBar.style.marginLeft = (posScroll * aspect).toString() + 'px';
    //     })
    // })
    // sectionContainer.addEventListener('mouseleave', () => {
    //     containerStartPos = posScroll;
    // })






    // // scrollBar
    //
    // let barContainer = document.querySelector('.testimonials__bar');
    //
    // let innerBar = document.querySelector('.testimonials__bar__slider');
    //
    // let toFill = opinions.clientWidth / opinions.scrollWidth;
    //
    // let filledAreaStr = (toFill * barContainer.clientWidth).toString() + 'px';
    //
    // innerBar.style.width = filledAreaStr;
    //
    // let innerBarPos = (barContainer.clientWidth - innerBar.clientWidth) / 2;
    //
    // innerBar.style.marginLeft = innerBarPos.toString() + 'px';
    //
    //
    //
    // // container
    //
    // let aspect =  barContainer.clientWidth / opinions.scrollWidth;



    // let posChange;
    //
    // sectionContainer.addEventListener('mouseenter', (event) => {
    //     const mouseStart = event.clientX;
    //
    //     barContainer.addEventListener('mousemove', (e) => {
    //         let x;
    //         // let coor = barContainer.getBoundingClientRect();
    //         // let posX = coor.x;
    //         let posChangeRange = barContainer.clientWidth - innerBar.clientWidth;
    //
    //         x = e.clientX; // aktualna pozycja kursora
    //         posChange = x - mouseStart + innerBarPos;
    //         // innerBar.addEventListener('mousemove', (event) => {
    //
    //         if(posChange < 0) {
    //             posChange = 0;
    //         } else if (posChange > posChangeRange) {
    //             posChange = posChangeRange;
    //         }
    //
    //         innerBar.style.marginLeft = (posChange).toString() + 'px';
    //
    //         opinions.scrollLeft = aspect * posChange; //need aspect to be equal to opi.sclWid / barCon.cliWid
    //
    //         // })
    //     })
    //
    //
    // })
    // barContainer.addEventListener('mouseleave', () => {
    //     innerBarPos = posChange;
    // })







    // // MOBILE VERSION
    //
    // let opinionsMob = document.querySelector('.testimonials__opinions--mobile');
    //
    // let areaInView = opinionsMob.clientWidth / opinionsMob.scrollWidth;
    //
    // let barContainerMobile = document.querySelector('.testimonials__bar--mobile');
    //
    // let innerBarMobile = document.querySelector('.testimonials__bar__slider--mobile');
    //
    // let aspectRatio = opinionsMob.scrollWidth / barContainerMobile.clientWidth;
    //
    // innerBarMobile.style.width = (areaInView * barContainerMobile.clientWidth).toString() + 'px';
    //
    // opinionsMob.addEventListener('scroll', () => {
    //     innerBarMobile.style.marginLeft = (opinionsMob.scrollLeft / aspectRatio).toString() + 'px';
    // })

});
