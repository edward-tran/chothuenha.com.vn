


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
// $('input[type-id=\'currency\']').trigger('onkeyup');



function formatPriceNumber($price) {
          var price = 0;
          switch($price) {
            case 0:
              var price = 0;
              break;
            case 500000:
              var price = '500 nghìn đồng';
              break;
            case 1000000:
              var price = '1 Triệu';
              break;
            case 1500000:
              var price = '1.5 Triệu';
              break;
            case 2000000:
              var price = '2 Triệu';
              break;
             case 2500000:
              var price = '2.5 Triệu';
              break;
            case 3000000:
              var price = '3 Triệu';
              break;
             case 3500000:
              var price = '3.5 Triệu';
              break;
            case 4000000:
              var price = '4 Triệu';
              break;
            case 4500000:
              var price = '4.5 Triệu';
              break;
            case 5000000:
              var price = '5 Triệu';
              break;
            case 5500000:
              var price = '5.5 Triệu';
              break;
            case 6000000:
              var price = '6 Triệu';
              break;
            case 6500000:
              var price = '6.5 Triệu';
              break;
            case 7000000:
              var price = '7 Triệu';
              break;
            case 7500000:
              var price = '7.5 Triệu';
              break;
            case 8000000:
              var price = '8 Triệu';
              break;
            case 8500000:
              var price = '8.5 Triệu';
              break;
            case 9000000:
              var price = '9 Triệu';
              break;
            case 9500000:
              var price = '9.5 Triệu';
              break;
            case 10000000:
              var price = '10 Triệu';
              break;
            case 10500000:
              var price = '10.5 Triệu';
              break;
            case 110000000:
              var price = '11 Triệu';
              break;
            case 11500000:
              var price = '11.5 Triệu';
              break;
            case 120000000:
              var price = '12 Triệu';
              break;
            case 12500000:
              var price = '12.5 Triệu';
              break;
            case 130000000:
              var price = '13 Triệu';
              break;
            case 13500000:
              var price = '13.5 Triệu';
              break;
            case 140000000:
              var price = '14 Triệu';
              break;
            case 14500000:
              var price = '14.5 Triệu';
              break;
            case 15000000:
              var price = '15 Triệu';
              break;
            case 15500000:
              var price = '15.5 Triệu';
              break;
            case 16000000:
              var price = '16 Triệu';
              break;
            case 16500000:
              var price = '16.5 Triệu';
              break;
            case 17000000:
              var price = '17 Triệu';
              break;
            case 17500000:
              var price = '17.5 Triệu';
              break;
            case 18000000:
              var price = '18 Triệu';
              break;
            case 18500000:
              var price = '18.5 Triệu';
              break;
            case 19000000:
              var price = '19 Triệu';
              break;
            case 19500000:
              var price = '19.5 Triệu';
              break;
            case 20000000:
              var price = '20 Triệu';
              break;
            case 20500000:
              var price = '20.5 Triệu';
              break;
            case 21000000:
              var price = '21 Triệu';
              break;
            case 21500000:
              var price = '21500000 Triệu';
              break;
            case 22000000:
              var price = '22 Triệu';
              break;
            case 22500000:
              var price = '22.5 Triệu';
              break;
            case 23000000:
              var price = '23 Triệu';
              break;
            case 23500000:
              var price = '3.5 Triệu';
              break;
            case 24000000:
              var price = '24.5 Triệu';
              break;
            case 25500000:
              var price = '24.5 Triệu';
              break;
            case 25000000:
              var price = '25 Triệu';
              break;
            case 25500000:
              var price = '25.5 Triệu';
              break;
            case 26000000:
              var price = '26 Triệu';
              break;
            case 26500000:
              var price = '26.5 Triệu';
              break;


              case 27000000:
              var price = '27 Triệu';
              break;
            case 27500000:
              var price = '27.5 Triệu';
              break;

             case 28000000:
              var price = '28 Triệu';
              break;
            case 28500000:
              var price = '28.5 Triệu';
              break;

             case 29000000:
              var price = '29 Triệu';
              break;
            case 29500000:
              var price = '29.5 Triệu';
              break;

             case 30000000:
              var price = '30 Triệu';
              break;
            case 30500000:
              var price = '30.5 Triệu';
              break;

             case 31000000:
              var price = '31 Triệu';
              break;
            case 31500000:
              var price = '31.5 Triệu';
              break;

             case 32000000:
              var price = '32 Triệu';
              break;
            case 32500000:
              var price = '32.5 Triệu';
              break;

             case 33000000:
              var price = '33 Triệu';
              break;
            case 33500000:
              var price = '33.5 Triệu';
              break;

             case 34000000:
              var price = '34 Triệu';
              break;
            case 34500000:
              var price = '34.5 Triệu';
              break;

             case 35000000:
              var price = '35 Triệu';
              break;
            case 35500000:
              var price = '35.5 Triệu';
              break;

             case 36000000:
              var price = '36 Triệu';
              break;
            case 36500000:
              var price = '36.5 Triệu';
              break;

             case 37000000:
              var price = '37 Triệu';
              break;
            case 37500000:
              var price = '37.5 Triệu';
              break;

             case 38000000:
              var price = '38 Triệu';
              break;
            case 38500000:
              var price = '38.5 Triệu';
              break;

             case 39000000:
              var price = '39 Triệu';
              break;
            case 39500000:
              var price = '39.5 Triệu';
              break;

             case 40000000:
              var price = '40 Triệu';
              break;
            case 40500000:
              var price = '40.5 Triệu';
              break;

             case 41000000:
              var price = '41 Triệu';
              break;
            case 41500000:
              var price = '41.5 Triệu';
              break;

             case 41000000:
              var price = '41 Triệu';
              break;
            case 41500000:
              var price = '41.5 Triệu';
              break;

             case 43000000:
              var price = '43 Triệu';
              break;
            case 43500000:
              var price = '43.5 Triệu';
              break;

             case 44000000:
              var price = '44 Triệu';
              break;
            case 44500000:
              var price = '44.5 Triệu';
              break;

             case 45000000:
              var price = '45 Triệu';
              break;
            case 45500000:
              var price = '45.5 Triệu';
              break;

             case 46000000:
              var price = '46 Triệu';
              break;
            case 46500000:
              var price = '46.5 Triệu';
              break;


             case 47000000:
              var price = '47 Triệu';
              break;
            case 47500000:
              var price = '47.5 Triệu';
              break;

             case 48000000:
              var price = '48 Triệu';
              break;
            case 48500000:
              var price = '48.5 Triệu';
              break;

             case 49000000:
              var price = '49 Triệu';
              break;
            case 49500000:
              var price = '49.5 Triệu';
              break;

             case 50000000:
              var price = '50 Triệu';
              break;
            case 50500000:
              var price = '50.5 Triệu';
              break;


            default:
              // code block
          }
          return price;
}
function goToByScroll(id, type = '') {
      // Remove "link" from the ID
      id = id.replace("link", "");
      // Scroll
      if(type == 'class') {
      	var element = $('.'+id);
      } else {
      	var element = $('#'+id);
      }
      
      $('html,body').animate({
          scrollTop: element.offset().top -200
      }, 'slow');
  }

function showLoading() {
	angular.element('.overlay__content').show();
}

function hideLoading() {
	angular.element('.overlay__content').hide();
}

function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

$(document).ready(function() {


	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	// Currency
	$('#form-currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#form-currency input[name=\'code\']').val($(this).attr('name'));

		$('#form-currency').submit();
	});

	// Language
	$('#form-language .language-select').on('click', function(e) {
		e.preventDefault();

		$('#form-language input[name=\'code\']').val($(this).attr('name'));

		$('#form-language').submit();
	});

	/* Search */
	$('#search input[name=\'search\']').parent().find('button').on('click', function() {
		var url = $('base').attr('href') + 'index.php?routing=product/search';

		var value = $('header #search input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('#search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header #search input[name=\'search\']').parent().find('button').trigger('click');
		}
	});

	// Menu
	$('#menu .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 10) + 'px');
		}
	});

	// Product List
	$('#list-view').click(function() {
		$('#content .product-grid > .clearfix').remove();

		$('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
		$('#grid-view').removeClass('active');
		$('#list-view').addClass('active');

		localStorage.setItem('display', 'list');
	});

	// Product Grid
	$('#grid-view').click(function() {
		// What a shame bootstrap does not take into account dynamically loaded columns
		var cols = $('#column-right, #column-left').length;

		if (cols == 2) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
		} else if (cols == 1) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
		} else {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
		}

		$('#list-view').removeClass('active');
		$('#grid-view').addClass('active');

		localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
		$('#list-view').addClass('active');
	} else {
		$('#grid-view').trigger('click');
		$('#grid-view').addClass('active');
	}

	// Checkout
	$(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
		if (e.keyCode == 13) {
			$('#collapse-checkout-option #button-login').trigger('click');
		}
	});

	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});
});

// Cart add remove functions
var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?routing=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				$('.alert-dismissible, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					// Need to set timeout otherwise it wont update the total
					setTimeout(function () {
						$('#cart > button').html('<span id="cart-total">' + json['total'] + '</span>');
					}, 100);

					$('html, body').animate({ scrollTop: 0 }, 'slow');

					$('#cart > ul').load('index.php?routing=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?routing=checkout/cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total">' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('routing') == 'checkout/cart' || getURLVar('routing') == 'checkout/checkout') {
					location = 'index.php?routing=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?routing=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?routing=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total">' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('routing') == 'checkout/cart' || getURLVar('routing') == 'checkout/checkout') {
					location = 'index.php?routing=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?routing=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?routing=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('routing') == 'checkout/cart' || getURLVar('routing') == 'checkout/checkout') {
					location = 'index.php?routing=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?routing=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?routing=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert-dismissible').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['status'] == true) {
					var $toast = toastr['success'](json['success']);
          $('span[data-post-id=\''+product_id+'\']').addClass('saved');
					//$('#content').parent().before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['status'] == false) {
					var $toast = toastr['warning'](json['success']);
				}
				// $('#wishlist-total span').html(json['total']);
				// $('#wishlist-total').attr('title', json['total']);

				// $('html, body').animate({ scrollTop: 0 }, 'slow');

				
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?routing=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert-dismissible').remove();

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#compare-total').html(json['total']);

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div>';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function() {
				this.request();
			});

			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);
