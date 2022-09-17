$(document).ready(function ($) {

	$('.home-slider').slick({
		lazyLoad: 'ondemand',
		autoplay: true,
		autoplaySpeed: 3000,
		fade: true,
		cssEase:'linear',
		dots: true,
		arrows: true,
		infinite: true
	});
	$('.slickbrand').slick({
		autoplay: true,
		autoplaySpeed: 6000,
		dots: false,
		arrows: true,
		infinite: true,
		speed: 300,
		slidesToShow: 8,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 5,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 5,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			}
		]
	});
	

	$('.slick_three_row').slick({
		autoplay: true,
		autoplaySpeed: 6000,
		dots: false,
		arrows: true,
		rows: 2,
		loop: false,
		infinite:false,
		speed: 300,
		slidesToShow: 4,
		slidesToScroll: 2,
		centerPadding: 15,
		responsive: [
			{
				breakpoint: 1366,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});
	$('.slick_normal').slick({
		autoplay: true,
		autoplaySpeed: 6000,
		dots: false,
		arrows: true,
		rows: 1,
		loop: false,
		infinite:false,
		speed: 300,
		slidesToShow: 4,
		slidesToScroll: 2,
		centerPadding: 15,
		responsive: [
			{
				breakpoint: 1366,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});

	$('.slickone').slick({
		autoplay: false,
		autoplaySpeed: 5000,
		dots: false,
		arrows: true,
		rows: 1,
		loop: false,
		infinite:false,
		speed: 300,
		draggable: false
	});

	$('.slick_gallery').each( function() {
		var $slider = $(this);

		if ($slider.length) {
			var currentSlide;
			var slidesCount;
			var sliderCounter = document.createElement('div');
			sliderCounter.classList.add('slider__counter');

			var updateSliderCounter = function(slick, currentIndex) {
				currentSlide = slick.slickCurrentSlide() + 1;
				slidesCount = slick.slideCount;
				$(sliderCounter).text(currentSlide + '/' +slidesCount)
			};

			$slider.on('init', function(event, slick) {
				$slider.append(sliderCounter);
				updateSliderCounter(slick);
			});

			$slider.on('afterChange', function(event, slick, currentSlide) {
				updateSliderCounter(slick, currentSlide);
			});

			$slider.slick({
				autoplay: true,
				autoplaySpeed: 3000,
				dots: true,
				arrows: false,
				rows: 1,
				loop: true,
				infinite:false,
				speed: 300,
				draggable: false
			});
		}
	});


	$('.slick_three_row_mini').slick({
		autoplay: true,
		autoplaySpeed: 6000,
		dots: false,
		arrows: true,
		rows: 3,
		loop: false,
		infinite:false,
		speed: 300,
		slidesToShow: 2,
		slidesToScroll: 1,
		centerPadding: 15,
		responsive: [
			{
				breakpoint: 1366,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});
	$('.slick_normal_mini').slick({
		autoplay: true,
		autoplaySpeed: 6000,
		dots: false,
		arrows: false,
		rows: 1,
		loop: false,
		infinite:false,
		speed: 300,
		slidesToShow: 2,
		slidesToScroll: 1,
		centerPadding: 15,
		responsive: [
			{
				breakpoint: 1366,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});


	
	/*
	Đoạn js này lấy template banner cho mobile ở trong snipets templatecart
	Đoạn template này chỉ hoạt động dưới mobile <= 767px
	Lưu ý khi sửa code section_bds_duan nhớ sửa code trong template này nữa
	Banner hiển thị trên pc xuống mobile sẽ bị xóa bỏ và thay bằng template này
	*/
	var wDW = $(window).width();
	var content = $('.data-banner').html();
	var datax = $('.col-data').html();
	if (wDW <= 767) {
		$('.data-banner').html('');
		$('.slickbanner >div').remove();
		var img_array = $('script[data-template="TemplateBanner"]').map(function() {
			return $(this).html();
		});
		for (var i=0; i<img_array.length; i++) {
			$('.slickbanner').append(''+img_array[i]+'');
		}
		$('.slickbanner').slick({
			autoplay: true,
			autoplaySpeed: 3000,
			dots: false,
			arrows: true,
			rows: 1,
			adaptiveHeight: true,
			infinite:false,
			speed: 300,
			draggable: false
		});
		
		var item_array = $('.col-data .itemx').map(function() {
			return $(this).html();
		});
		
		for (var i=0; i<item_array.length; i++) {
			$('.data-mobile .inwrp').append('<div class="item"><div class="itemx">'+item_array[i]+'</div></div>');
		}
		$('.inwrp').slick({
			autoplay: true,
			autoplaySpeed: 3000,
			dots: false,
			arrows: true,
			rows: 1,
			adaptiveHeight: true,
			infinite:false,
			speed: 300,
			draggable: false
		});
		 $('.col-data').html('');

	} else {
		$('.slickbanner >div, .data-mobile .inwrp').remove();
		$('.data-banner').html(content);
	}
	
	/*Tabblog*/
    function activeTab(obj){
        $('.section_blog ul li a').removeClass('active');
        $(obj).addClass('active');
        var id = $(obj).attr('href');
        $('.content .tabcontent').removeClass('active');
        $(id).addClass('active');
    }
 
    $('.section_blog ul li a').click(function(){
		$('.urltab a').attr('href', $(this).attr('data-href'));
        activeTab(this);
        return false;
    });
	
	





});