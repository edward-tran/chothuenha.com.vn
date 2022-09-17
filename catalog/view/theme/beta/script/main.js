  //active href///
$(function(){
    //active href///
  var touch = false;
  $("[href]").each(function() {
    if (this.href == window.location.href) {
        $(this).parent().addClass("active");
        }
    });
//end active href///

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


        $(document).ready(function() {

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
        });
        //--></script> 