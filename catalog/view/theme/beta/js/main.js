  //active href///
$(function(){


    let slider_price = $("#slider-range-price").slider({
      range: true,
      min: 0,
      step: 500000,
      max: 50000000,
      values: [0, 50000000],
      slide: function(event, ui) {
        $('#amount_price').html(`${formatPriceNumber(ui.values[0])} - ${formatPriceNumber(ui.values[1])}`);
        $('.search-item-price').html(`<span>${formatPriceNumber(ui.values[0])} - ${formatPriceNumber(ui.values[1])}</span>`);
        $('#min_price').val(ui.values[0]);
        $('#max_price').val(ui.values[1]);
      }
    });

    let slider_acreage = $("#slider-range-acreage").slider({
      range: true,
      min: 0,
      step: 5,
      max: 500,
      values: [0, 50000000],
      slide: function(event, ui) {
        $('#amount_acreage').html(`${ui.values[0]} - ${ui.values[1]}` + ' m2');

        angular.element('.search-item-acreage').html(`<span>${ui.values[0]} - ${ui.values[1]}` + ' m2</span>');

        $('#min_acreage').val(ui.values[0]);
        $('#max_acreage').val(ui.values[1]);
      }
    });
    
	$("body").on("click", ".dropdown-btn", function(e){
	// $('#navbar_main_mobile .navigation').find('li').has('ul >li').children('.dropdown-btn').on('click', function() {
        $(this).parent('li').toggleClass('open');

        $(this).parent('li').siblings().removeClass('open');

    });

 	$('.mobile-nav-toggler').on('click', function(){
	    $('body').addClass('mobile-menu-visible');
	  });

	$(window).scroll(function(){
        var h = $(window).scrollTop();
        var width = $(window).width();
        if(h > 35){
            $('body').addClass('main-ontop');
        }else{
            $('body').removeClass('main-ontop');
        }
	});

	// ===== Scroll to Top ==== 
    $(window).scroll(function() {
        if ($(this).scrollTop() >= 100) {        // If page is scrolled more than 50px
            $('#return-to-top').fadeIn(200);    // Fade in the arrow
        } else {
            $('#return-to-top').fadeOut(200);   // Else fade out the arrow
        }
    });
    $('#return-to-top').click(function() {      // When arrow is clicked
        $('body,html').animate({
            scrollTop : 0                       // Scroll to top of body
        }, 500);
    });

    //active href///
  var touch = false;
  $("[href]").each(function() {
	    if (this.href == window.location.href) {
	        $(this).parent().addClass("active");
	        $(this).parents('.has-sub').addClass("active open");
	    }
    });
//end active href///
$("body").on("click", ".pagination li a", function(e){
	let page = '&page='+$(this).attr("href");
	let url = $('input[name=\'url_pagination\']').val()+page;
	$.ajax({
		url: url,
		type: 'GET',
		dataType: 'html',
		beforeSend: function() {
			showLoading();
		},
		success: function(json) {
			$('.append_pagination').html(json);
			hideLoading();
			$('body,html').animate({
	            scrollTop : 0                       // Scroll to top of body
	        }, 500);
	        let url_current = $('input[name=\'url_current\']').val() +page;
	        window.history.pushState({ path: url_current }, '', url_current);
		}
	});
	e.preventDefault();
});


// $('.pagination li a').on('click', function(e) {
// 	$.ajax({
// 		url: $('input[name=\'url_pagination\']').val()+'&page='+$(this).attr("href"),
// 		type: 'GET',
// 		dataType: 'html',
// 		beforeSend: function() {
// 			showLoading();
// 		},
// 		success: function(json) {
// 			$('.append_pagination').html(json);
// 			hideLoading();
// 			$('body,html').animate({
// 	            scrollTop : 0                       // Scroll to top of body
// 	        }, 500);
// 		}
// 	});
// 	e.preventDefault();
// });

});
//end active href///

function formatCurrency(g, b) {
	if (window.event) {
		key = window.event.keyCode
	} else {
		if (b) {
			key = b.which
		} else {
			return true
		}
	}
	if (key == 35 || key == 36 || key == 37 || key == 38 || key == 39 || key == 40 || key == 46) {
		return true
	}
	var k = g.selectionStart;
	var c = g.selectionEnd;
	var f = $(g).val();
	var d = Math.max(0, f.substring(0, k).split(",").length - 1);
	f = f.toString().replace(/\$|\,/g, "");
	if (isNaN(f)) {
		f = "0"
	}
	var h = f != Math.abs(f);
	f = Math.abs(f);
	f = Math.floor(f * 100 + 0.50000000001);
	var a = f%100;
	f = Math.floor(f / 100).toString();
	if (a < 10) {
		a = "0" + a
	}
	for (i = 0; i < Math.floor((f.length - (1 + i)) / 3); i++) {
		f = f.substring(0, f.length - (4 * i + 3)) + "," + f.substring(f.length - (4 * i + 3))
	}
	d = Math.max(0, Math.max(0, f.substring(0, k).split(",").length - 1) - d);
	$(g).val((h ? "-": "") + f);
	g.setSelectionRange(k + d, c + d)
}

$(document).ready(function ($) {

	$(".numbers-row").append('<div class="dec qtybutton">-</div><div class="inc qtybutton">+</div>');
	$(".qtybutton").on("click", function () {
	    var $button = $(this);
	    var oldValue = $button.parent().find(".quantity").val();
	    if ($button.text() == "+") {
	        var newVal = parseFloat(oldValue) + 1;
	    } else {
	        // Don't allow decrementing below zero
	        if (oldValue > 0) {
	            var newVal = parseFloat(oldValue) - 1;
	        } else {
	            newVal = 0;
	        }
	    }
	    $button.parent().find(".quantity").val(newVal);
	});

	$('.action_pro').click(function(){
		$('.advance_option.pc').toggleClass('runFunction offFunction').slideToggle();
		$(this).find('.fa').toggleClass('fa-angle-up fa-angle-down');
	});

	$('.option_top span').click(function(){
		$('.option_top span').removeClass('active');
		$(this).addClass('active');
	});

	
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

window.awe = window.awe || {};
awe.init = function () {
	awe.showPopup();
	awe.hidePopup();	
};
$(document).ready(function ($) {
	"use strict";
	awe_backtotop();
	awe_category();
	awe_lazyloadImage();
	awe_tab();
	convertprice();

	$('.filter-content .aside-item .aside-title').click(function(e){
		if ($(this).parent().hasClass('active')) {
			$('.filter-content .aside-item').removeClass('active');
		} else {
			$('.filter-content .aside-item').removeClass('active');
			$(this).parent().addClass('active');
		}
	})
	$('.filter-content .aside-item .title').click(function(e){
		$(this).parent().toggleClass('active');
	});

});


$('.close-pop').click(function() {
	$('#popup-cart').removeClass('opencart');
	$('body').removeClass('opacitycart');
});
$(document).on('click','.overlay, .close-popup, .btn-continue, .fancybox-close', function() {   
	// hidePopup('.awe-popup'); 	
	setTimeout(function(){
		$('.loading').removeClass('loaded-content');
	},500);
	return false;
})
function awe_lazyloadImage() {
	// var ll = new LazyLoad({
	// 	elements_selector: ".lazyload",
	// 	load_delay: 500,
	// 	threshold: 0
	// });
} window.awe_lazyloadImage=awe_lazyloadImage;
function awe_showLoading(selector) {
	var loading = $('.loader').html();
	$(selector).addClass("loading").append(loading); 
}  window.awe_showLoading=awe_showLoading;
function awe_hideLoading(selector) {
	$(selector).removeClass("loading"); 
	$(selector + ' .loading-icon').remove();
}  window.awe_hideLoading=awe_hideLoading;
function awe_showPopup(selector) {
	$(selector).addClass('active');
}  window.awe_showPopup=awe_showPopup;
function awe_hidePopup(selector) {
	$(selector).removeClass('active');
}  window.awe_hidePopup=awe_hidePopup;
awe.hidePopup = function (selector) {
	$(selector).removeClass('active');
}
$(document).on('click','.overlay, .close-window, .btn-continue, .fancybox-close', function() {   
	awe.hidePopup('.awe-popup'); 
	setTimeout(function(){
		$('.loading').removeClass('loaded-content');
	},500);
	return false;
})
var wDWs = $(window).width();
if (wDWs < 1199) {
	/*Remove html mobile*/
	$('.quickview-product').remove();
}
function awe_convertVietnamese(str) { 
	str= str.toLowerCase();
	str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a"); 
	str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e"); 
	str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i"); 
	str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o"); 
	str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u"); 
	str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y"); 
	str= str.replace(/đ/g,"d"); 
	str= str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g,"-");
	str= str.replace(/-+-/g,"-");
	str= str.replace(/^\-+|\-+$/g,""); 
	return str; 
} window.awe_convertVietnamese=awe_convertVietnamese;
function awe_category(){
	$('.nav-category .fa-plus').click(function(e){
		$(this).toggleClass('fa-minus fa-plus');
		$(this).parent().toggleClass('active');
	});
	$('.nav-category .fa-minus').click(function(e){
		$(this).toggleClass('fa-plus');
		$(this).parent().toggleClass('active');
	});
} window.awe_category=awe_category;


function awe_backtotop() { 
	$(window).scroll(function() {
		$(this).scrollTop() > 200 ? $('.backtop').addClass('show') : $('.backtop').removeClass('show')
	});
	$('.backtop').click(function() {
		return $("body,html").animate({
			scrollTop: 0
		}, 800), !1
	});
} window.awe_backtotop=awe_backtotop;
function awe_tab() {
	$(".e-tabs:not(.not-dqtab)").each( function(){
		$(this).find('.tabs-title li:first-child').addClass('current');
		$(this).find('.tab-content').first().addClass('current');
		$(this).find('.tabs-title li').click(function(e){
			var tab_id = $(this).attr('data-tab');
			var url = $(this).attr('data-url');
			$(this).closest('.e-tabs').find('.tab-viewall').attr('href',url);
			$(this).closest('.e-tabs').find('.tabs-title li').removeClass('current');
			$(this).closest('.e-tabs').find('.tab-content').removeClass('current');
			$(this).addClass('current');
			$(this).closest('.e-tabs').find("#"+tab_id).addClass('current');

		});    
	});
} window.awe_tab=awe_tab;
$('.dropdown-toggle').click(function() {
	$(this).parent().toggleClass('open'); 	
}); 
$('.btn-close').click(function() {
	$(this).parents('.dropdown').toggleClass('open');
}); 
$(document).on('keydown','#qty, .number-sidebar',function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
$(document).on('click','.qtyplus',function(e){
	e.preventDefault();   
	fieldName = $(this).attr('data-field'); 
	var currentVal = parseInt($('input[data-field='+fieldName+']').val());
	if (!isNaN(currentVal)) { 
		$('input[data-field='+fieldName+']').val(currentVal + 1);
	} else {
		$('input[data-field='+fieldName+']').val(0);
	}
});
$(document).on('click','.qtyminus',function(e){
	e.preventDefault(); 
	fieldName = $(this).attr('data-field');
	var currentVal = parseInt($('input[data-field='+fieldName+']').val());
	if (!isNaN(currentVal) && currentVal > 1) {          
		$('input[data-field='+fieldName+']').val(currentVal - 1);
	} else {
		$('input[data-field='+fieldName+']').val(1);
	}
});
$('.menubutton').click(function(e){
	e.stopPropagation();
	$('.wrapmenu_right').toggleClass('open_sidebar_menu');
	$('.opacity_menu').toggleClass('open_opacity');
});
$('.opacity_menu').click(function(e){
	$('.wrapmenu_right').removeClass('open_sidebar_menu');
	$('.opacity_menu').removeClass('open_opacity');
});
$('.open-filters').click(function(e){
	e.stopPropagation();
	$(this).toggleClass('openf');
	$('.dqdt-sidebar').toggleClass('openf');
});
$(".menubar_pc").click(function(){ 
	$('.wrapmenu_full').slideToggle('fast');
	$('.wrapmenu_full, .cloed').toggleClass('open_menu');
	$('.dqdt-sidebar, .open-filters').removeClass('openf')
});
$(".cloed").click(function(){ 
	$(this).toggleClass('open_menu');
	$('.wrapmenu_full').slideToggle('fast');
});
$(".opacity_menu").click(function(){ 
	$('.opacity_menu').removeClass('open_opacity');
});
if ($('.dqdt-sidebar').hasClass('openf')) {
	$('.wrapmenu_full').removeClass('open_menu');
} 
$('.ul_collections li > .fa').click(function(){
	$(this).parent().toggleClass('current');
	$(this).toggleClass('fa-chevron-up fa-chevron-down');
	$(this).next('ul').slideToggle("fast");
	$(this).next('div').slideToggle("fast");
});
$('.searchion').mouseover(function() {
	$('.searchmini input').focus();                    
})
$('.quenmk').on('click', function() {
	$('.h_recover').slideToggle();
});
$('a[data-toggle="collapse"]').click(function(e){
	if ($(window).width() >= 767) { 
		// Should prevent the collapsible and default anchor linking 
		// behavior for screen sizes equal or larger than 768px.
		e.preventDefault();
		e.stopPropagation();
	}    
});

/*************************Convertpricefunction**********************/
/*******************************************************************/

function convertprice(price) { 
	$('.product_convert').each( function(){
		var pricetext = $(this).find('.price-box .product-price-convert').text().replace('₫', ''),
			so = pricetext.toString().length,
			price = pricetext.replace(/\./g, ""), //xóa dấu .
			text = ConvertNumberToText(price);
    	$(this).find('.product-price-convert').html(text);
	});
}window.convertprice=convertprice;


function IsNumber(number) {
    for (var i = 0; i < number.length; i++) {
        var temp = number.substring(i, i + 1);
        if (!(temp >= "0" && temp <= "9")) {
            return false;
        }
    }
    return true;
}

function ConvertNumberToText(price) {
    if (!IsNumber(price))
        return "";
    price = price;
    var priceTy = parseInt(price / 1000000000, 0)
    var priceTrieu = parseInt((price % 1000000000) / 1000000, 0)
    var priceNgan = parseInt(((price % 1000000000)) % 1000000 / 1000, 0)
    var priceDong = parseInt(((price % 1000000000)) % 1000000 % 1000, 0)
    var strTextPrice = ""
    if (priceTy > 0 && parseInt(price, 0) > 900000000) {
        if (priceTrieu > 0) {
            getTrieu = "," + priceTrieu / 100;
        } else {
            getTrieu = '';
        }
        strTextPrice = strTextPrice + priceTy + getTrieu + " Tỷ ";
    }
    if (priceTy == 0 && priceTrieu > 0) {
        if (priceNgan > 0) {
            getNgan = "," + priceNgan / 100;
        } else {
            getNgan = '';
        }
        strTextPrice = strTextPrice + priceTrieu + getNgan + " Triệu ";
    }
    if (priceTrieu == 0 && priceNgan > 0) {
        if (priceDong > 0) {
            getDong = "," + priceDong / 100;
        } else {
            getDong = '';
        }
        strTextPrice = strTextPrice + priceNgan + getDong + " Ngàn ";
    }
    if (priceNgan == 0 && priceDong > 0) {
        strTextPrice = strTextPrice + priceDong + " Đồng";
    }
    strTextPrice = strTextPrice.replace(/\./g, "");
    return strTextPrice;
}




// slide//
$(document).ready(function(){
	


	// $("#min_m2,#max_m2").on('change', function () {

	//   var min_m2_range = parseInt($("#min_m2").val());

	//   var max_m2_range = parseInt($("#max_m2").val());

	//   if (min_m2_range > max_m2_range) {
	// 	$('#max_m2').val(min_m2_range);
	//   }

	//   $("#slider-range").slider({
	// 	values: [min_m2_range, max_m2_range]
	//   });
	  
	// });


	// $("#min_m2,#max_m2").on("paste keyup", function () {                                        

	//   var min_m2_range = parseInt($("#min_m2").val());

	//   var max_m2_range = parseInt($("#max_m2").val());
	  
	//   if(min_m2_range == max_m2_range){

	// 		max_m2_range = min_m2_range + 100;
			
	// 		$("#min_m2").val(min_m2_range);		
	// 		$("#max_m2").val(max_m2_range);
	//   }

	//   $("#slider-range").slider({
	// 	values: [min_m2_range, max_m2_range]
	//   });

	// });


	// $(function () {
	//   $("#slider-range-m2").slider({
	// 	range: true,
	// 	orientation: "horizontal",
	// 	min: 0,
	// 	max: 500,
	// 	values: [0, 500],
	// 	step: 10,

	// 	slide: function (event, ui) {
	// 	  if (ui.values[0] == ui.values[1]) {
	// 		  return false;
	// 	  }
	// 	  $("#min_m2").val(ui.values[0]);
	// 	  $("#max_m2").val(ui.values[1]);
	// 	  $(".select-text-content_m2").text(ui.values[0]  +" "+ "-" + " "+ ui.values[1] + "m2");
	// 	}
	//   });
	//   // $("#min_m2").val($("#slider-range").slider("values", 0));
	//   // $("#max_m2").val($("#slider-range").slider("values", 1));
	// });

	// $(function () {
	//   $("#slider-range-price").slider({
	// 	range: true,
	// 	orientation: "horizontal",
	// 	min: 0,
	// 	max: 20000,
	// 	values: [0, 20000],
	// 	step: 100,

	// 	slide: function (event, ui) {
	// 	  if (ui.values[0] == ui.values[1]) {
	// 		  return false;
	// 	  }
	// 	  $("#min_price").val(ui.values[0]);
	// 	  $("#max_price").val(ui.values[1]);
	// 	  $(".select-text-content_price").text(ui.values[0]  +" "+ "-" + " "+ ui.values[1] + "Tỷ");
	// 	}
	//   });
	//   // $("#min_m2").val($("#slider-range").slider("values", 0));
	//   // $("#max_m2").val($("#slider-range").slider("values", 1));
	// });


});