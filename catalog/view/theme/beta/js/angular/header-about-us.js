const video = document.querySelectorAll('.header-about-us video, .top-section video, .header-services__video-container video');

for(let i = 0; i < video.length; i++) {
    if(! ('none' === video[i].style.display)) {
        video[i].addEventListener('canplay', () => {
            video[i].play();
        })
    }
}

$(document).ready(function() {
    $( ".lazy-swiper-images" ).each(function() {

        let img = $(this);

        setTimeout(function() {
            img.attr('srcset', img.data('srcset'));

            img.addClass("loaded");
            img.removeClass("lazy-swiper-images");
        }, 1500);
    });
})
