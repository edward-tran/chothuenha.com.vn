$(document).ready(function() {
    function trackTitleToShowOrHide(id){
        if (document.querySelector(id).getBoundingClientRect().top < 0.85 * window.outerHeight) {
            document.querySelector(id).classList.add('showText');
        } else {
            document.querySelector(id).classList.remove('showText');
        }
    }

    window.addEventListener('scroll', () => {

        trackTitleToShowOrHide('.main-text__content__paragraphs');
        trackTitleToShowOrHide('.main-text__title__h2');
        trackTitleToShowOrHide('.main-text__content__link');
        trackTitleToShowOrHide('.main-text__title__bottom__rating');
        trackTitleToShowOrHide('.main-text__title__bottom__image-container');
        trackTitleToShowOrHide('.main-text__title__header__images');
        trackTitleToShowOrHide('.main-text__title__header__text');
    })

    window.addEventListener('scroll', () => {


    })
})
