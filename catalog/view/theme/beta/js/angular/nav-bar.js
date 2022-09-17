$(document).ready(function () {

    const navBar = document.querySelector('.nav-bar');
    const mainPageNavBar = document.querySelector('.default-page .nav-bar');
    const defPage = document.querySelector('.default-page');

    let scroll_start = 0;
    let startchange = $('.nav-bar-white-start');
    let offset = startchange.offset();
    let logo = $(".logo-desktop");
    let transparent = $('.nav-bar').data('transparent');

    if(transparent == true){

        changeNavbar();

        if (startchange.length){
            $(document).scroll(function() {
                changeNavbar();
            });
        }

    }else{

        $(window).scroll(function() {
            if ($(window).scrollTop() <= 10) {
                $(".nav-bar").addClass('nav-bar-white--padding-top');
            }else{
                $(".nav-bar").removeClass('nav-bar-white--padding-top');
            }
        });

        $(".nav-bar").addClass('nav-bar-white');
        $(".nav-bar").addClass('nav-bar-white--padding-top');
        $(".hamburger-title").addClass('hamburger-title--dark');
        $(".toggle-roadbar__hamburger").addClass('toggle-roadbar__hamburger--dark');
        logo.attr('src',logo.attr('data-src-black'));
    }

    function changeNavbar(){
        scroll_start = $(document).scrollTop();
        if (document.querySelector('.toggle-roadbar__hamburger__container').classList.contains('open')) {

        }else{
            if(scroll_start > offset.top) {
                $(".nav-bar").addClass('nav-bar-white');
                $(".hamburger-title").addClass('hamburger-title--dark');
                $(".toggle-roadbar__hamburger").addClass('toggle-roadbar__hamburger--dark');
                logo.attr('src',logo.attr('data-src-black'));
            } else {
                $('.nav-bar').removeClass('nav-bar-white');
                $(".hamburger-title").removeClass('hamburger-title--dark');
                $(".toggle-roadbar__hamburger").removeClass('toggle-roadbar__hamburger--dark');
                logo.attr('src',logo.attr('data-src-white'));
            }
        }
    }

    var lastScrollTop = 0;
    // element should be replaced with the actual target element on which you have applied scroll, use window in case of no target element.
    window.addEventListener("scroll", function () { // or window.addEventListener("scroll"....
        mainPageNavBar.style.marginTop = "0";
        var st = window.pageYOffset || document.documentElement.scrollTop; // Credits: "https://github.com/qeremy/so/blob/master/so.dom.js#L426"
        if (st > lastScrollTop) {
            // downscroll code
            if (!document.querySelector('.mobile-menu').classList.contains('mm-active')) {
                // mainPageNavBar.classList.remove('visible');
                navBar.classList.remove('visible');
            }

        } else {
            // upscroll code
            // mainPageNavBar.classList.add('visible');
            navBar.classList.add('visible');
        }
        lastScrollTop = st <= 0 ? 0 : st; // For Mobile or negative scrolling
    }, false);

    document.querySelector('.toggle-roadbar').addEventListener('click', function () {
        document.querySelector('.toggle-roadbar__hamburger__container').classList.toggle('open');

        let watchdog = document.querySelector('.hamburger-title');


        if (document.querySelector('.toggle-roadbar__hamburger__container').classList.contains('open')) {
            document.querySelector('.mobile-menu').classList.add('mm-active');
            document.querySelector('.background--empty').classList.toggle('background--black');
            document.querySelector('.nav-bar').style.top = "0 !important";
            if (!watchdog.classList.contains('hamburger-title--dark')) {
                $(".hamburger-title").addClass('hamburger-title--dark');
                $(".toggle-roadbar__hamburger").addClass('toggle-roadbar__hamburger--dark');
                logo.delay(200).queue(function (next) {
                    $(this).attr('src',logo.attr('data-src-black')); next();
                });
                document.querySelector('.toggle-roadbar__hamburger').addEventListener('click', function () {
                    logo.attr('src',logo.attr('data-src-white'));
                    $(".toggle-roadbar__hamburger").removeClass('toggle-roadbar__hamburger--dark');
                    $(".hamburger-title").removeClass('hamburger-title--dark');
                })
            }
            // logo.attr('src',logo.attr('data-src-white'));
            // $(".hamburger-title").removeClass('hamburger-title--dark');
            // $(".toggle-roadbar__hamburger").removeClass('toggle-roadbar__hamburger--dark');
        } else {
            document.querySelector('.mobile-menu').classList.remove('mm-active');
            document.querySelector('.background--empty').classList.toggle('background--black');
            for (let i = 0; i<3; i++) {
                dropdowns[i].classList.remove('expand-dropdown');
            }
            // scroll_start = $(document).scrollTop();
            // if(scroll_start > offset.top) {
            //     $(".nav-bar").addClass('nav-bar-white');
            //     $(".hamburger-title").addClass('hamburger-title--dark');
            //     $(".toggle-roadbar__hamburger").addClass('toggle-roadbar__hamburger--dark');
            //     logo.attr('src',logo.attr('data-src-black'));
            // } else {
            //     $('.nav-bar').removeClass('nav-bar-white');
            //     $(".hamburger-title").removeClass('hamburger-title--dark');
            //     $(".toggle-roadbar__hamburger").removeClass('toggle-roadbar__hamburger--dark');
            //     logo.attr('src',logo.attr('data-src-white'));
            // }
        }

    });

    let reveals = document.querySelectorAll('.mobile-menu li .dropdown-reveal');
    let revealsImgs = document.querySelectorAll('.mobile-menu li .dropdown-reveal img');
    let dropdowns = document.querySelectorAll('.mobile-menu li .dropdown-container');

    for (let i = 0; i<3; i++) {
        reveals[i].addEventListener('click', () => {
            for (let j = 0; j<3; j++) {
                if(i !== j) {
                    if(dropdowns[j].classList.contains('expand-dropdown')) {
                        dropdowns[j].classList.remove('expand-dropdown');
                        revealsImgs[j].style.animation = 'showHide .8s';
                        revealsImgs[j].style.transform = 'rotate(0)';
                    }
                }
            }

            dropdowns[i].classList.toggle('expand-dropdown');

            if(dropdowns[i].classList.contains('expand-dropdown')) {
                revealsImgs[i].style.animation = 'hideShow .8s';
                revealsImgs[i].style.transform = 'rotate(180deg)';
            } else {
                revealsImgs[i].style.animation = 'showHide .8s';
                revealsImgs[i].style.transform = 'rotate(0)';
            }
        });
    }

    document.querySelector('.toggle-roadbar').addEventListener('click', () => {
        for (let i = 0; i<3; i++) {
            if(!document.querySelector('.mobile-menu').classList.contains('.mm-active')) {
                revealsImgs[i].style.transform = 'rotate(0)';
            }
        }
    })

    window.addEventListener('scroll', () => {
        if ((document.documentElement.scrollHeight - document.documentElement.clientHeight) <= -1 * defPage.getBoundingClientRect().top + 200) {
            navBar.classList.remove('visible');
        }
    })

    let page = document.querySelector('#our-projects-page, #case-study, #contact-page, #career-page');


    if(page.classList.contains('default-page')) {
        navBar.classList.add('nav-bar-white');
        logo.attr('src',logo.attr('data-src-black'));
    }
    // });

    // expand mobile menu elements when arrow is clicked



});
