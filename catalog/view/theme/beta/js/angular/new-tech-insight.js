$(document).ready(function() {

    let swiperContainer = document.querySelector('.new-tech-insight--articles .swiper-wrapper');
    let data;

    // async function fetchAsync (url) {
    //     let response = await fetch(url);
    //     data = await response.json();
    //     let length;
    //     if(data.length > 10) {
    //         length = 10;
    //     } else {
    //         length = data.length;
    //     }
    //     for(let i = 0; i < length; i++) {
    //         let dateArray = data[i]['date'].split('-');
    //         const formatter = new Intl.DateTimeFormat('en', { month: 'short' });
    //         const month = formatter.format(new Date(2003, dateArray[1] - 1, 12));
    //         let insertHtml =
    //             `<div class="swiper-slide">
    //                 <a href="${data[i]['link']}" target="_blank">
    //                     <div class="nti__slide">
    //                         <div class="nti__thumbnail">
    //                             <img data-srcset="${data[i]['_embedded']['wp:featuredmedia']['0']['media_details']['sizes']['gillion-landscape-small']['source_url'] + ' 420w, ' + data[i]['_embedded']['wp:featuredmedia']['0']['media_details']['sizes']['tablet-800']['source_url']}">
    //                         </div>
    //                         <h2>${data[i]['title']['rendered']}</h2>
    //                         <p>${month + ' ' + dateArray[2].substr(0, 2) + ', ' + dateArray[0].substr(2)}</p>
    //                     </div>
    //                 </a>
    //             </div>`;
    //         swiperContainer.insertAdjacentHTML('beforeend', insertHtml);
    //     }

    //     for(let i = 0; i < length; i++) {


    //         setTimeout(function () {
    //             let img = document.querySelectorAll('.new-tech-insight--articles .swiper-slide img:first-of-type');
    //             img[i].setAttribute('srcset',img[i].getAttribute('data-srcset'));
    //             img[i].classList.add('loaded');
    //         }, 1500);
    //     }

    //     var newTechInsightSwiper = new Swiper('.new-tech-insight-swiper--articles', {
    //         freeMode: false,
    //         freeModeMomentum: false,
    //         watchSlidesVisibility: true,
    //         slidesPerView: 3.5,
    //         centeredSlides: false,
    //         mousewheel: {
    //             forceToAxis: true,
    //             invert: true
    //         },
    //         breakpoints: {
    //             2560: {
    //                 slidesPerView: 2.8,
    //             },
    //             2240: {
    //                 slidesPerView: 2.5,
    //             },
    //             1920: {
    //                 slidesPerView: 2.15,
    //             },
    //             1650: {
    //                 slidesPerView: 1.9,
    //             },
    //             1450: {
    //                 slidesPerView: 1.55,
    //             },
    //             1200: {
    //                 slidesPerView: 2.65,
    //             },
    //             1000: {
    //                 slidesPerView: 2.1,
    //             },
    //             800: {
    //                 slidesPerView: 1.7,
    //             },
    //             650: {
    //                 slidesPerView: 1.35,
    //             },
    //             500: {
    //                 slidesPerView: 1,
    //             },
    //             400: {
    //                 slidesPerView: 0.9,
    //             },
    //             350: {
    //                 slidesPerView: 0.8,
    //             }

    //         },
    //         scrollbar: {
    //             el: '.swiper-scrollbar',
    //             hide: false,
    //         },
    //     });
    // }

    // if(document.querySelector('.new-tech-insight--articles--dev')) {
    //     fetchAsync('https://invotech.co/blog/wp-json/wp/v2/posts?categories=6&_embed');
    // } else if(document.querySelector('.new-tech-insight--articles--design')) {
    //     fetchAsync('https://invotech.co/blog/wp-json/wp/v2/posts?categories=171&_embed');
    // }   else if(document.querySelector('.new-tech-insight--articles--consulting')) {
    //     fetchAsync('https://invotech.co/blog/wp-json/wp/v2/posts?categories=8&_embed');
    // } else {
    //     fetchAsync('https://invotech.co/blog/wp-json/wp/v2/posts?_embed');
    // }

    var newTechInsightSwiper = new Swiper('.new-tech-insight-swiper', {
        freeMode: false,
        freeModeMomentum: false,
        watchSlidesVisibility: true,
        slidesPerView: 3.5,
        centeredSlides: false,
        mousewheel: {
            forceToAxis: true,
            invert: true
        },
        breakpoints: {
            2280: {
                slidesPerView: 3,
            },
            1920: {
                slidesPerView: 2.6,
            },
            1650: {
                slidesPerView: 2.1,
            },
            1450: {
                slidesPerView: 1.85,
            },
            1200: {
                slidesPerView: 2.75,
            },
            1000: {
                slidesPerView: 2.25,
            },
            800: {
                slidesPerView: 2,
            },
            650: {
                slidesPerView: 1.6,
            },
            500: {
                slidesPerView: 1.3,
            },
            400: {
                slidesPerView: 1,
            },
            350: {
                slidesPerView: 0.9,
            }
        },
        scrollbar: {
            el: '.swiper-scrollbar',
            hide: false,
        },
    });

    var newTechInsightSwiper = new Swiper('.new-tech-insight-swiper--resources', {
        freeMode: false,
        freeModeMomentum: false,
        watchSlidesVisibility: true,
        slidesPerView: 3.25,
        centeredSlides: false,
        mousewheel: {
            forceToAxis: true,
            invert: true
        },
        breakpoints: {
            2560: {
                slidesPerView: 3,
            },
            1650: {
                slidesPerView: 2.5,
            },
            1400: {
                slidesPerView: 2.125,
            },
            1200: {
                slidesPerView: 1.75,
            },
            800: {
                slidesPerView: 1.375,
            },
            650: {
                slidesPerView: 0.925,
            },
            500: {
                slidesPerView: 0.9,
            },
            400: {
                slidesPerView: 0.8375,
            },
            350: {
                slidesPerView: 0.8,
            }

        },
        scrollbar: {
            el: '.swiper-scrollbar',
            hide: false,
        },
    });

    var newTechInsightSwiper = new Swiper('.new-tech-insight-swiper--services', {
        freeMode: false,
        freeModeMomentum: false,
        watchSlidesVisibility: true,
        slidesPerView: 1.1,
        centeredSlides: true,
        mousewheel: {
            forceToAxis: true,
            invert: true
        },
        breakpoints: {
            2560: {
                slidesPerView: 1.1,
            },
            // 1650: {
            //     slidesPerView: 0.9,
            // },
            // 1450: {
            //     slidesPerView: 1,
            // },
            1200: {
                slidesPerView: 2,
            },
            800: {
                slidesPerView: 1.5,
            },
            650: {
                slidesPerView: 0.875,
            },
            500: {
                slidesPerView: 0.875,
            },
            400: {
                slidesPerView: 0.75,
            },
            350: {
                slidesPerView: 0.67,
            }

        },
        scrollbar: {
            el: '.swiper-scrollbar',
            hide: false,
        },
    });



    $( ".lazy-swiper-images" ).each(function() {

        let img = $(this);

        setTimeout(function() {
            img.attr('src', img.data('src'));

            img.addClass("loaded");
            img.removeClass("lazy-swiper-images");
        }, 1500);
    });
})
