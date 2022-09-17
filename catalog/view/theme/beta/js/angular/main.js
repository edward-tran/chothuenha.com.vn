  //active href///
$(function(){

    

    


    var $toggle_mob_link = $('.menu_horizontal nav').find('li').children('.menu-drop');
    $toggle_mob_ul = $('ul'),
    $toggle_mob_link.on('click', function (e) {
            e.preventDefault();
            var $that = $(this);
            $that.toggleClass('is-active');
            $that.next($toggle_mob_ul).slideToggle();
            // $that.parent().siblings().find($toggle_mob_link).removeClass('is-active');
            // $that.parent().siblings().find($toggle_mob_ul).slideUp();
            // $that.closest('.we-mega-menu-submenu').find($toggle_mob_link).not($that).removeClass('is-active');
            // $that.closest('.we-mega-menu-submenu').find($toggle_mob_link).not($that).next($toggle_mob_ul).slideUp()
    });


    //active href///
  var touch = false;
  $("[href]").each(function() {
    if (this.href == window.location.href) {
        $(this).parent().addClass("active");
        }
    });
//end active href///
 /* Preloader */
    var win = $(window);
    win.on('load', function () {
        $('.page-loader').delay(1500).fadeOut('slow');
    });

    // // meanmenu
    // $('#mobile-menu').meanmenu({
    //     meanMenuContainer: '.mobile-menu',
    //     meanScreenWidth: "992"
    // });

    
    $('.mobile-menu').on('click', function(){
        $('body').addClass('active_menu');
    });
    $('.site-overlay').on('click', function(){
        $('body').removeClass('active_menu');
    });

    var top_offset = 0;
    // $('.main-menu nav ul').onePageNav({
    //     currentClass: 'active',
    //     scrollOffset: top_offset,
    // });
  
    // data - background
    $("[data-background]").each(function () {
        $(this).css("background-image", "url(" + $(this).attr("data-background") + ")")
    })

    // sticky header
    $(window).on('scroll', function () {
        var scroll = $(window).scrollTop();
        if (scroll < 245) {
            $(".header-sticky").removeClass("sticky-menu");
        } else {
            $(".header-sticky").addClass("sticky-menu");
        }
    });

    // mainSlider
    function mainSlider() {
        var BasicSlider = $('.slider-active');
        BasicSlider.on('init', function (e, slick) {
            var $firstAnimatingElements = $('.single-slider:first-child').find('[data-animation]');
            doAnimations($firstAnimatingElements);
        });
        BasicSlider.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
            var $animatingElements = $('.single-slider[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
            doAnimations($animatingElements);
        });
    //     BasicSlider.slick({
    //         autoplay: false,
    //         autoplaySpeed: 10000,
    //         dots: false,
    //         fade: true,
    //         arrows: false,
    //         responsive: [
    //             {
    //                 breakpoint: 767,
    //                 settings: {
    //                     dots: false,
    //                     arrows: false
    //                 }
    //             }
    // ]
    //     });

        function doAnimations(elements) {
            var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            elements.each(function () {
                var $this = $(this);
                var $animationDelay = $this.data('delay');
                var $animationType = 'animated ' + $this.data('animation');
                $this.css({
                    'animation-delay': $animationDelay,
                    '-webkit-animation-delay': $animationDelay
                });
                $this.addClass($animationType).one(animationEndEvents, function () {
                    $this.removeClass($animationType);
                });
            });
        }
    }
    mainSlider();

    // owlCarousel
    // if($('.owl-carousel').length >0) {
    // $('.owl-carousel').owlCarousel({
    //     loop: true,
    //     margin: 0,
    //     items: 1,
    //     navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
    //     nav: true,
    //     dots: false,
    //     responsive: {
    //         0: {
    //             items: 1
    //         },
    //         767: {
    //             items: 3
    //         },
    //         992: {
    //             items: 5
    //         }
    //     }
    // });
    // }

    /* magnificPopup img view */
    // $('.popup-image').magnificPopup({
    //     type: 'image',
    //     gallery: {
    //         enabled: true
    //     }
    // });

    // /* magnificPopup video view */
    // $('.popup-video').magnificPopup({
    //     type: 'iframe'
    // });

    // isotop
    // $('.grid').imagesLoaded(function () {
    //     // init Isotope
    //     var $grid = $('.grid').isotope({
    //         itemSelector: '.grid-item',
    //         percentPosition: true,
    //         masonry: {
    //             // use outer width of grid-sizer for columnWidth
    //             columnWidth: '.grid-item',
    //         }
    //     });
    // });

    // filter items on button click
    $('.portfolio-menu').on('click', 'button', function () {
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({
            filter: filterValue
        });
    });

    //for menu active class
    $('.portfolio-menu button').on('click', function (event) {
        $(this).siblings('.active').removeClass('active');
        $(this).addClass('active');
        event.preventDefault();
    });

    //slick-activation
  //   $('.h1-testimonials-active').slick({
  //       dots: false,
  //       infinite: true,
  //       speed: 300,
  //       slidesToShow: 3,
  //       arrows:false,
  //       slidesToScroll: 1,
  //       responsive: [
  //           {
  //               breakpoint: 1024,
  //               settings: {
  //                   slidesToShow: 2,
  //                   slidesToScroll: 3,
  //                   infinite: true,
  //                   dots: false
  //               }
  //   },
  //           {
  //               breakpoint: 600,
  //               settings: {
  //                   slidesToShow: 2,
  //                   slidesToScroll: 2
  //               }
  //   },
  //           {
  //               breakpoint: 480,
  //               settings: {
  //                   slidesToShow: 1,
  //                   slidesToScroll: 1
  //               }
  //   }
  //   // You can unslick at a given breakpoint now by adding:
  //   // settings: "unslick"
  //   // instead of a settings object
  // ]
  //   });

    //slick-activation
  //   $('.h1-screenshots-active').slick({
  //       dots: true,
  //       infinite: true,
  //       speed: 300,
  //       slidesToShow: 3,
  //       arrows:false,
  //       slidesToScroll: 1,
  //       centerMode: true,
  //       centerPadding: '0px',
  //       responsive: [
  //           {
  //               breakpoint: 1024,
  //               settings: {
  //                   slidesToShow: 2,
  //                   slidesToScroll: 3,
  //                   infinite: true,
  //                   dots: false
  //               }
  //   },
  //           {
  //               breakpoint: 600,
  //               settings: {
  //                   slidesToShow: 2,
  //                   slidesToScroll: 2
  //               }
  //   },
  //           {
  //               breakpoint: 480,
  //               settings: {
  //                   slidesToShow: 1,
  //                   slidesToScroll: 1
  //               }
  //   }
  //   // You can unslick at a given breakpoint now by adding:
  //   // settings: "unslick"
  //   // instead of a settings object
  // ]
  //   });







    /*-------------------------------------
add remove active pricing button
------------------------------------ */
    $(function () {
        var $pl_btns = $('.pl-btn').on('click', function () {
            $pl_btns.removeClass('active');
            $(this).addClass('active');
        });
    });

    /*-------------------------------------
    add remove active pricing table
    ------------------------------------ */
    $('#pn_1').on('click', function () {
        // changing on pricing content
        $('.single-pricing').removeClass('active');
        $('.prt_1').addClass('active');
    });

    $('#pn_2').on('click', function () {
        // changing on pricing content
        $('.single-pricing').removeClass('active');
        $('.prt_2').addClass('active');
    });



});
//end active href///

//menu fix top//
$(window).scroll(function(){
        var h = $(window).scrollTop();
        var width = $(window).width();
        // if(width > 767){
            if(h > 35){
                $('body').addClass('main-header-ontop');
            }else{
                $('body').removeClass('main-header-ontop');
            }
        // }
});
//end menu fix top//

//  $("#site-menu-handle").on(function () {
//         // $(this).trigger('trigger-menu-mobile');
//         alert('sdfs');
// });
// $(".menu-mobile .b-close").click(function () {
//     $("#site-menu-handle").trigger('trigger-menu-mobile');
// });
// $(document).on("click",".effect-bg",function () {
//     $("#site-menu-handle").trigger('trigger-menu-mobile');
// })
        function onChanged(name) {
            $('.'+name).remove();
        }
        function onShopNameChanged(value) {
            $('.shopname').remove();
            var title, slug;

            //Lấy text từ thẻ input title 
            title = value;

            // normalize Domain
            slug = normalizeDomain(title);

            if (!!slug) {
                var shop_name_show_to_customer = slug + '.' + 'lohishop.com';
                $('#shop_name_show_to_customer').html('<span class="has-success">Cửa hàng online của bạn: <span id="result">' + shop_name_show_to_customer + '</span></span>');
            } else {
                $('#shop_name_show_to_customer').html('');
            }

            var text = '<input type="hidden" name="shop_domain" value="' + slug + '">';
            $('#shop_domain').html(text);
            
            $.ajax({
                url: "index.php?routing=eliwi/website/autoCheckShopDomain",
                type: 'POST',
                data: {
                    shop_domain: slug
                },
                error: function () {
                    console.log('Check shop existing failed! (code: 0');
                },
                success: function (response) {
                    if (response && response.status == true) {
                        var shop_exist_message = '<span id="span_message" class="has-danger">Tên cửa hàng đã tồn tại</span>';
                        $('#shop_exist_message').html(shop_exist_message);
                       disableRegisterButton();                    
                    } else {
                        $('#span_message').remove();
                        enableRegisterButton();
                    }
                }
            });
        }
       
        $("#form-register").validate({
            ignore: ".ignore",
            rules: {
                shopname: "required",
                phone: {
                    required: true,
                    pattern: /^0[0-9-+]+$|^84[0-9-+]+$|^\+84[0-9-+]+$/
                },
                email: {
                    required: true,
                    pattern: /^(\s*)([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+(\s*)$/
                },
                password:{
                    required: true,
                    minlength: 6
                },
                ct_hiddenRecaptcha: {
                    required: '' == 1
                }
            },
            messages: {
                shopname: "Vui lòng nhập tên cửa hàng",
                phone: {
                    required: "Vui lòng nhập số điện thoại",
                    pattern: "Số điện thoại không đúng định dạng",
                    minlength: "Số điện thoại nhập tối thiểu 10 số"
                },
                email: {
                    required: "Vui lòng nhập email",
                    pattern: "Email không đúng định dạng"
                },
                password:{
                    required: "Vui lòng nhập mật khẩu",
                    minlength: "Mật khẩu dài ít nhất 6 ký tự"
                },
                ct_hiddenRecaptcha: {
                    required: "Vui lòng nhập captcha",
                }
            }
        });
        $('#form-register input').on('blur keyup', function() {

            if ($("#form-register").valid()) {
                var value = $('#form-register').find('input[name="shopname"]').val();
                onShopNameChanged(value);
                enableRegisterButton();
            } else {
                disableRegisterButton();
            }
        });
        $('#email').on('blur keyup', function () {
            if (!$("#email").valid()) {
                disableNextButtonToLoginStep2();
                return;
            }
            enableNextButtonToLoginStep2();
        });
        $('#forgot_email').on('blur keyup', function () {
            if (!$("#forgot_email").valid()) {
                disableForgottenPwdButton();
                return;
            }
            enableForgottenPwdButton();
        });
        function enableNextButtonToLoginStep2() {
            $('#btn-next').prop("disabled", false);
        }
        function disableNextButtonToLoginStep2() {
            $('#btn-next').prop("disabled", true);
        }
        function enableRegisterButton() {
            $('#register').prop("disabled", false);
        }
        function disableRegisterButton() {
            $('#register').prop("disabled", true);
        }
        function disableForgottenPwdButton() {
            $('#btn-forgotten-pwd').prop("disabled", true);
        }
        function normalizeDomain(slug) {
            // Đổi chữ hoa thành chữ thường
            slug = slug.toLowerCase();

            // Đổi ký tự có dấu thành không dấu
            slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
            slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
            slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
            slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
            slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
            slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
            slug = slug.replace(/đ/gi, 'd');

            // Đổi khoảng trắng thành ký tự gạch ngang
            slug = slug.replace(/[\s]+/g, "-");

            // Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
            slug = slug.replace(/[\-]+/g, '-');

            // Xóa các ký tự đặt biệt
            slug = slug.replace(/[^0-9a-z\-]/g, '');

            // Xóa các ký tự gạch ngang ở đầu và cuối
            slug = slug.replace(/^[\-]+|[\-]+$/g, '');

            return slug;
        }

        function disableLoginButton() {
            $('#btn-login').prop("disabled", true);
        }
        function showLoginFailedAlert() {
            $('#loading').hide();
            $('#login_failed_alert').fadeIn();
        }


        function onPasswordChanged() {
            hideLoginFailedAlert();
            hideStep1FailedAlert();
        }


        function onEmailChanged() {
            hideLoginFailedAlert();
            hideStep1FailedAlert();
        }
        function hideStep1FailedAlert() {
                $('#step_1_alert').fadeOut();
        }   

            function hideLoginFailedAlert() {
                $('#login_failed_alert').fadeOut();
        }
        $(document).ready(function() {


            $('#password_view').click(function () {
                if ($('#pass_field').prop("type") === "text") {
                    $('#pass_field').prop("type", "password");
                } else {
                    $('#pass_field').prop("type", "text");
                }
            });

            
            

            var modal_login = $("#modal-login-view");
            var modal_register = $("#modal-register-view");
            $(document).on("click", "#open-register, .btn-trial-new", function() {
                modal_register.addClass("animated");
                
                modal_register.addClass('in');
                $('body').addClass('modal-open');
                modal_register.show();
                modal_login.modal("hide");
               
                
                

                modal_register.modal({backdrop: 'static', keyboard: false})  
                return false;
              });


            $('.modal-close').on('click', function(){
                 $('body').removeClass('modal-open');
                 modal_register.removeClass('in');
                    modal_register.hide();
            });

            $(document).on("click", "#text-login", function() {
                modal_login.addClass("animated");
                modal_login.modal("show");
                return false;
            });

           $('.search-area img').on('click', function() {
               $(this).next().toggleClass('active');
           });

            if($('.product-latest').length >0) {
                $('.product-latest').owlCarousel({
                    responsive:{
                      0:{
                          items:1,
                      },
                      750:{
                          items:2,
                      },
                      970:{
                          items:2,
                      },
                      1170:{
                          items:4,
                      }
                  },
                    lazyLoad:true,
                    autoplay: true,
                    autoplayTimeout: 4000,
                    loop: false,
                    singleItem: true,
                    nav: false,
                    dots: false,
                    rewindNav : false,
                    margin: 10,
                    navText: [
                    '<i class="fa fa-chevron-left fa-5x"></i>',
                    '<i class="fa fa-chevron-right fa-5x"></i>'
                    ]
                });
            }

            if($('.screen-mobile').length >0) {
                $('.screen-mobile').owlCarousel({
                    responsive:{
                      0:{
                          items:1,
                      },
                      750:{
                          items:2,
                      },
                      970:{
                          items:2,
                      },
                      1170:{
                          items:1,
                      }
                  },
                    lazyLoad:true,
                    autoplay: true,
                    autoplayTimeout: 4000,
                    loop: false,
                    singleItem: true,
                    nav: false,
                    dots: false,
                    rewindNav : false,
                    margin: 0,
                    navText: [
                    '<i class="fa fa-chevron-left fa-5x"></i>',
                    '<i class="fa fa-chevron-right fa-5x"></i>'
                    ]
                });
            }

        });
        //--></script> 