$(document).ready(function() {
    let spaceToFill = document.querySelector('.media-section__small-bar');
    let scrollValue = document.querySelector('.media-section__bar');
    let spaceToScroll = document.querySelector('.media-section');

    let screenW = window.screen.width;

    spaceToFill.style.width = ((screenW / 1500) * 100).toString() + '%';

    // alert(window.getComputedStyle(spaceToFill).width);

    spaceToScroll.addEventListener('scroll', () => {
        scrollValue.style.paddingLeft = ((spaceToScroll.scrollLeft / (1500 - screenW)) * (318 - parseInt(window.getComputedStyle(spaceToFill).width))).toString() + 'px';
    });


})
