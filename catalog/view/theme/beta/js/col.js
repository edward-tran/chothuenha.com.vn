$(window).on('popstate', function() {
	location.reload(true);	
});

var selectedSortby;
var tt = 'Thứ tự';

var filter = new Bizweb.SearchFilter()

if(colId > 0){
	filter.addValue("collection", "collections", colId, "AND");
}
function toggleFilter(e) {
	_toggleFilter(e);
	renderFilterdItems();
	doSearch(1);
}
function _toggleFilterdqdt(e) {

	var $element = $(e);
	var group = 'Khoảng giá';
	var field = 'price_min';
	var operator = 'OR';	 
	var value = $element.attr("data-value");	

	filter.deleteValuedqdt(group, field, value, operator);
	filter.addValue(group, field, value, operator);
	renderFilterdItems();
	doSearch(1);
}

function _toggleFilter(e){
	var $element = $(e);
	var group = $element.attr("data-group");
	var field = $element.attr("data-field");
	var text = $element.attr("data-text");
	var value = $element.attr("value");
	var operator = $element.attr("data-operator");
	var filterItemId = $element.attr("id");



	if (!$element.is(':checked')) {
		filter.deleteValue(group, field, value, operator);
	}
	else{
		filter.addValue(group, field, value, operator);
	}

	$(".catalog_filters li[data-handle='" + filterItemId + "']").toggleClass("active");
}

function renderFilterdItems() {
	var $container = $(".filter-container__selected-filter-list ul");
	$container.html("");

	$(".filter-container input[type=checkbox]").each(function(index) {
		if ($(this).is(':checked')) {
			var id = $(this).attr("id");
			var name = $(this).closest("label").text();

			addFilteredItem(name, id);
		}
	});

	if($(".filter-container input[type=checkbox]:checked").length > 0)
		$(".filter-container__selected-filter").show();
	else
		$(".filter-container__selected-filter").hide();
}
function addFilteredItem(name, id) {
	var filteredItemTemplate = "<li class='filter-container__selected-filter-item' for='{3}'><a href='javascript:void(0)' onclick=\"{0}\"><i class='fa fa-times'></i> {1}</a></li>";
	filteredItemTemplate = filteredItemTemplate.replace("{0}", "removeFilteredItem('" + id + "')");
	filteredItemTemplate = filteredItemTemplate.replace("{1}", name);
	filteredItemTemplate = filteredItemTemplate.replace("{3}", id);
	var $container = $(".filter-container__selected-filter-list ul");
	$container.append(filteredItemTemplate);
}
function removeFilteredItem(id) {
	$(".filter-container #" + id).trigger("click");
}

function doSearch(page, options) {
	if(!options) options = {};
	//NProgress.start();
	$('.aside.aside-mini-products-list.filter').removeClass('active');
	awe_showPopup('.loading');
	filter.search({
		view: selectedViewData,
		page: page,
		sortby: selectedSortby,
		success: function (html) {
			var $html = $(html);
			// Muốn thay thẻ DIV nào khi filter thì viết như này
			var $categoryProducts = $($html[0]); 


			$(".category-products").html($categoryProducts.html());
			pushCurrentFilterState({sortby: selectedSortby, page: page});
			awe_hidePopup('.loading');				  
			awe_lazyloadImage(); 
			$('.filter_container_wrap').removeClass('open');
			$('.add_to_cart').click(function(e){
				e.preventDefault();
				var $this = $(this);						   
				var form = $this.parents('form');						   
				$.ajax({
					type: 'POST',
					url: '/cart/add.js',
					async: false,
					data: form.serialize(),
					dataType: 'json',
					error: addToCartFail,
					beforeSend: function() {  
						if(window.theme_load == "icon"){
							awe_showLoading('.btn-addToCart');
						} else{
							awe_showPopup('.loading');
						}
					},
					success: addToCartSuccess,
					cache: false
				});
			});
			convertprice();
			$('html, body').animate({
				scrollTop: $('.bg_collection').offset().top
			}, 0);
			$('.filter-content .aside-item').removeClass('active');
			resortby(selectedSortby);
			MapsCallBack();

			$('#sort-by .ul_col .content_ul li').click(function(e){
				$(".sortpage").removeClass('active');
				e.preventDefault();
			});


		}
	});		

}

function sortby(sort) {			 
	switch(sort) {
		case "price-asc":
			selectedSortby = "price_min:asc";					   
			break;
		case "price-desc":
			selectedSortby = "price_min:desc";
			break;
		case "alpha-asc":
			selectedSortby = "name:asc";
			break;
		case "alpha-desc":
			selectedSortby = "name:desc";
			break;
		case "created-desc":
			selectedSortby = "created_on:desc";
			break;
		case "created-asc":
			selectedSortby = "created_on:asc";
			break;
		default:
			selectedSortby = "";
			break;
	}

	doSearch(1);
}

function resortby(sort) {
	switch(sort) {				  
		case "price_min:asc":
			tt = "Giá tăng dần";
			break;
		case "price_min:desc":
			tt = "Giá giảm dần";
			break;
		case "name:asc":
			tt = "Tên A → Z";
			break;
		case "name:desc":
			tt = "Tên Z → A";
			break;
		case "created_on:desc":
			tt = "Hàng mới nhất";
			break;
		case "created_on:asc":
			tt = "Hàng cũ nhất";
			break;
		default:
			tt = "Mặc định";
			break;
	}			   
	$('.sortpage h2 span').html(tt);
	$('#sort-by2 > ul > li > span').html(tt);
}


function _selectSortby(sort) {
	resortby(sort);
	switch(sort) {
		case "price-asc":
			selectedSortby = "price_min:asc";
			break;
		case "price-desc":
			selectedSortby = "price_min:desc";
			break;
		case "alpha-asc":
			selectedSortby = "name:asc";
			break;
		case "alpha-desc":
			selectedSortby = "name:desc";
			break;
		case "created-desc":
			selectedSortby = "created_on:desc";
			break;
		case "created-asc":
			selectedSortby = "created_on:asc";
			break;
		default:
			selectedSortby = sort;
			break;
	}
}

function toggleCheckbox(id) {
	$(id).click();
}

function pushCurrentFilterState(options) {

	if(!options) options = {};
	var url = filter.buildSearchUrl(options);
	var queryString = url.slice(url.indexOf('?'));			  
	if(selectedViewData == 'data_list'){
		queryString = queryString + '&view=list';	
		$('.button-view-mode').removeClass('active');
		$('.button-view-mode.view-mode-list').addClass('active');
	}
	else{
		queryString = queryString + '&view=grid';	
		$('.button-view-mode').removeClass('active');
		$('.button-view-mode.view-mode-grid').addClass('active');
	}

	pushState(queryString);
}

function pushState(url) {
	window.history.pushState({
		turbolinks: true,
		url: url
	}, null, url)
}
function switchView(view) {			  
	switch(view) {
		case "list":
			$('.button-view-mode').removeClass('active');
			$('.button-view-mode.view-mode-list').addClass('active');
			selectedViewData = "data_list";					   
			break;
		default:
			$('.button-view-mode').removeClass('active');
			$('.button-view-mode.view-mode-grid').addClass('active');
			selectedViewData = "data";
			break;
	}			   
	var url = window.location.href;
	var page = getParameter(url, "page");
	if(page != '' && page != null){
		doSearch(page);
	}else{
		doSearch(1);
	}
}

function selectFilterByCurrentQuery() {
	var isFilter = false;
	var url = window.location.href;
	var queryString = decodeURI(window.location.search);
	var filters = queryString.match(/\(.*?\)/g);

	if(queryString) {
		isFilter = true;
	}
	if(filters && filters.length > 0) {
		filters.forEach(function(item) {
			item = item.replace(/\(\(/g, "(");
			if(item.lastIndexOf(">") >= 0){					   					  
				var mStart1 = item.lastIndexOf("(>");
				var mStart2 = item.lastIndexOf(" AND");
				var mStart = item.slice(mStart1+2, mStart2);
				var mStop1 = item.lastIndexOf("<");
				var mStop2 = item.lastIndexOf(")");
				var mStop = item.slice(mStop1+1, mStop2);					  
				
				$('#start input').val(Bizweb.formatMoney(Number(mStart) + 1, "{{amount_no_decimals_with_comma_separator}}₫"));
														 $('#stop input').val(Bizweb.formatMoney(Number(mStop) - 1, "{{amount_no_decimals_with_comma_separator}}₫"));
																								 
																								 var maxx = 100000000/10;
																								 var slider = $('#slider');


																			  $('.filter-value').attr('data-value',item);

									  var $element = $('.filter-value');
				var group = 'Khoảng giá';
				var field = 'price_min';
				var operator = 'OR';	 
				var value = item;	



				filter.deleteValuedqdt(group, field, value, operator);
				filter.addValue(group, field, value, operator);
				renderFilterdItems();
				doSearch(1);



			}else{
				var element = $(".aside-item input[value='" + item + "']");

				element.attr("checked", "checked");
				_toggleFilter(element);
			}
		});

		isFilter = true;
	}

	var sortOrder = getParameter(url, "sortby");
	if(sortOrder) {
		_selectSortby(sortOrder);
	}

	if(isFilter) {
		doSearch(cuPage);
		renderFilterdItems();

	}
}

function getParameter(url, name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(url);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

$( document ).ready(function() {
	selectFilterByCurrentQuery();
	$('.filter-group .filter-group-title').click(function(e){
		$(this).parent().toggleClass('active');
	});

	$('.filter-mobile').click(function(e){
		$('.aside.aside-mini-products-list.filter').toggleClass('active');
	});

	$('#show-admin-bar').click(function(e){
		$('.aside.aside-mini-products-list.filter').toggleClass('active');
	});

	$('.filter-container__selected-filter-header-title').click(function(e){
		$('.aside.aside-mini-products-list.filter').toggleClass('active');
	});
});





$('.filter-item--check-box input').change(function(e){
	var $this = $(this);
	toggleFilter($this);
})
$('a#filter-value').click(function(e){
	var $this = $(this);
	_toggleFilterdqdt($this);
})
$('.dosearch').click(function(e){
	var $data = $(this).attr('data-onclick');
	doSearch($data);
})
$('.awe_sortby').on('click',function(e){
	var $data = $(this).attr('data-onclick');
	sortby($data);

})
function filterItemInList(object) {
	q = object.val().toLowerCase();
	object.parent().next().find('li').show();
	if (q.length > 0) {
		object.parent().next().find('li').each(function() {
			if ($(this).find('label').attr("for").indexOf(q) == -1)
				$(this).hide();
		})
	}
}

/*Sắp xếp trang collection*/
$('#sort-by .ul_col .content_ul li').click(function(e){
	$(".sortpage").removeClass('active');
	e.preventDefault();

});

$('#sort-by2 .ul_col .content_ul li').click(function(e){
	$(".sortpage").removeClass('active');
	e.preventDefault();

});


/*Sắp xếp trang collection*/
$('#sort-by2 .ul_col li span').click(function(e){

	$('.content_ul').css('display', 'block');
	e.preventDefault();

});
$('#sort-by2 .ul_col .content_ul li').click(function(e){

	$(".content_ul").css('display', 'none');
	e.preventDefault();

});
var wDWs = $(window).width();
if (wDWs > 991) {
	$('.action_pro').click(function(e){
		$('.moreaction').toggleClass('active');
		e.preventDefault();
	});
}


$(document).mouseup(function(e) {
	var container = $(".aside-item, .sortpage");
	// if the target of the click isn't the container nor a descendant of the container
	if (!container.is(e.target) && container.has(e.target).length === 0) {
		container.removeClass('active');
	}
});


function MapsCallBack() {	
	var geocoder;
	var map;
	var bounds = new google.maps.LatLngBounds();

	function initialize() {
		map = new google.maps.Map(
			document.getElementById("map_canvas"), {
				center: new google.maps.LatLng(37.4419, -122.1419),
				zoom: 40,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
		geocoder = new google.maps.Geocoder();

		for (i = 0; i < locations.length; i++) {
			geocodeAddress(locations, i);
		}
	}
	google.maps.event.addDomListener(window, "load", initialize);
	initialize();
	function geocodeAddress(locations, i) {
		var title = locations[i][0];
		var address = locations[i][1];
		var url = locations[i][2];
		var image = locations[i][3];
		var thueorban = locations[i][4];
		geocoder.geocode({
			'address': locations[i][1]
		},

						 function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var marker = new google.maps.Marker({
					icon: "//bizweb.dktcdn.net/thumb/thumb/100/393/384/themes/804718/assets/marker.png",
					map: map,
					position: results[0].geometry.location,
					title: title,
					animation: google.maps.Animation.DROP,
					address: address,
					url: url,
					image: image,
					thueorban: thueorban
				})
				infoWindow(marker, map, title, address, url, image, thueorban);
				bounds.extend(marker.getPosition());
				map.fitBounds(bounds);
			} else {
			}
		});
	}

	function infoWindow(marker, map, title, address, url, image, thueorban) {
		google.maps.event.addListener(marker, 'click', function() {
			var html = "<div class='popup_map'><div class='img_thumb'><span class='lb'>"+thueorban+"</span><img src="+image+" alt='image'/></div><div class='rightct'><h3><a href="+url+">" + title + "</a></h3><span>" + address + "</span></div></div></div>";
			iw = new google.maps.InfoWindow({
				content: html,
				maxWidth: 400
			});
			iw.open(map, marker);
		});
	}

	function createMarker(results) {
		var marker = new google.maps.Marker({
			icon: "//bizweb.dktcdn.net/thumb/thumb/100/393/384/themes/804718/assets/marker.png",
			map: map,
			position: results[0].geometry.location,
			title: title,
			animation: google.maps.Animation.DROP,
			address: address,
			url: url,
			image: image,
			thueorban: thueorban
		})
		bounds.extend(marker.getPosition());
		map.fitBounds(bounds);
		infoWindow(marker, map, title, address, url, image, thueorban);
		return marker;
	}
} window.MapsCallBack=MapsCallBack;