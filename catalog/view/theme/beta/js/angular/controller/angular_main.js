

var app = angular.module('myApp',[
  'ngAnimate',
  'ngMaterial',
  'material.svgAssetsCache',
  'chieffancypants.loadingBar',
  'ngMessages',
  'angularFileUpload',
  'md.data.table']).config(function(
  $mdThemingProvider,
 $mdIconProvider,
 $mdToastProvider,
 cfpLoadingBarProvider
 ){

  
$mdToastProvider.addPreset('simpleToast', {
  options: function() {
    return {
      template:
        '<md-toast>' +
          '<div class="md-toast-content">' +
            'This is a custom preset' +
          '</div>' +
        '</md-toast>',
      controllerAs: 'toast',
      bindToController: true
    };
  }
});
$mdIconProvider
          .defaultIconSet("./assets/svg/avatars.svg", 128)
          .icon("menu", "./assets/svg/menu.svg", 24)
          .icon("share", "./assets/svg/share.svg", 24)
          .icon("google_plus", "./assets/svg/google_plus.svg", 512)
          .icon("lock", "./assets/svg/lock.svg", 512)
          .icon("hangouts", "./assets/svg/hangouts.svg", 512)
          .icon("twitter", "./assets/svg/twitter.svg", 512)
          .icon("phone", "./assets/svg/phone.svg", 512)
          .icon("info", "./assets/svg/info.svg", 512)
          .icon("delete", "./assets/svg/delete.svg", 512)
          .icon("cart", "./assets/svg/cart.svg", 512)
          .icon("account", "./assets/svg/account.svg", 512)
          .icon("payment", "./assets/svg/payment.svg", 512)
          .icon("done", "./assets/svg/done.svg", 512)
          .icon("shopping", "./assets/svg/shopping.svg", 512)
          .icon("avatars", "./assets/svg/avatar-icons.svg", 24)
          .icon("check", "./assets/svg/baseline-check-24px.svg", 24);
          // $mdThemingProvider.theme('default')
          // .primaryPalette('teal')
          // .accentPalette('red');
});
app.directive('loading', function () {
      return {
        restrict: 'E',
        replace:true,
        template: '" width="50" height="50" />SEND MAIL...</div>',
        link: function (scope, element, attr) {
              scope.$watch('loading', function (val) {
                  if (val)
                      $(element).show();
                  else
                      $(element).hide();
              });
        }
      }
  });

app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('<@');
    $interpolateProvider.endSymbol('@>');
});

// app.config(['$mdThemingProvider', function($mdThemingProvider) {
//     $mdThemingProvider.generateThemesOnDemand(false);
// }])

// app.config(function($mdThemingProvider) {
//   $mdThemingProvider.setDefaultTheme('none');
// });


app.controller('mainController', function(
  $scope,
  $http,
  $compile,
  $timeout,
  $mdDialog,
  $mdSidenav,
  $log,
  $filter
  ) {

  // $scope.myDate = new Date();
  // $scope.isOpen = false;


  $scope.myDate = {
    formatDate: function(date) {
      var m = moment(date);
      return m.isValid() ? m.format('dd/mm/Y') : '';
    }
  };

    // Menu Mobile//
    // $scope.menu_mobiles = [];
    // $scope.getMenuMobile = function() {
    //       $http({
    //         method: 'GET',
    //         url: 'index.php?routing=extension/module/menu/getMenuMobile',
    //         dataType: 'HTML',
    //         headers : {'Content-Type': 'application/x-www-form-urlencoded'},
    //       }).then(function(response){
    //         $scope.menu_mobiles = response.data['menus'];
    //         console.log($scope.menu_mobiles);
    //         return;
    //       }).catch(function(response) {
    //        return false;
    //     }).finally(function() {
    //       return false;
    //     });
    // }
    // $scope.getMenuMobile();

     

     

      function buildDelayedToggler(navID) {
        return debounce(function() {
          // Component lookup should always be available since we are not using `ng-if`
          $mdSidenav(navID)
            .toggle()
            .then(function () {
              $log.debug("toggle " + navID + " is done");
            });
        }, 200);
      }

      function buildToggler(navID) {
        return function() {
          // Component lookup should always be available since we are not using `ng-if`
          $mdSidenav(navID)
            .toggle()
            .then(function () {
              $log.debug("toggle " + navID + " is done");
            });
        };
      }


      function debounce(func, wait, context) {
        var timer;

        return function debounced() {
          var context = $scope,
              args = Array.prototype.slice.call(arguments);
          $timeout.cancel(timer);
          timer = $timeout(function() {
            timer = undefined;
            func.apply(context, args);
          }, wait || 10);
        };
      }

      function DialogController($scope, $mdDialog) {
        $scope.hide = function () {
          $mdDialog.hide();
        };

        $scope.cancel = function () {
          $mdDialog.cancel();
        };

        $scope.answer = function (answer) {
          $mdDialog.hide(answer);
        };
      }

      $scope.cancelDialog = function ($event, $id = '') {
            showLoading();
            $mdDialog.cancel();
            setTimeout(function() {
              hideLoading();
              if($id !== '') {
                angular.element('#'+$id).remove();
              }
            }, 500);
            
      };

       $scope.redirect = function($url) {
        setTimeout(function() {
         window.location = $url;
        }, 1000);
      }



      $scope.toggleLeft = buildDelayedToggler('left');
      $scope.toggleRight = buildToggler('right');
      $scope.isOpenRight = function(){
        return $mdSidenav('right').isOpen();
      };

      // $scope.toggleRight = function() {
      //   angular.element('body').addClass('mobile-menu-visible');
      // }


      $scope.closeMenuRight = function () {
        // Component lookup should always be available since we are not using `ng-if`
        $mdSidenav('right').close()
          .then(function () {
            $log.debug("close RIGHT is done");
          });
      };

      $scope.showFormReister = function($event) {
          angular.element('#dialog-form-login').remove();
          angular.element('#dialog-form-register').remove();
          showLoading();
          angular.element($event.target).button('loading');
          if(_class == 'account-login' || _class == 'account-register') {
            window.location = base+'dang-ky.html';
            return;
          }
          $http({
            method: 'GET',
            url: 'index.php?routing=account/register/showFormRegister',
            dataType: 'HTML',
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function(response){
            let html = response.data;
            let temp = $compile(html)($scope);
            angular.element('body').prepend(temp);
            $mdDialog.show({
              controller: DialogController,
              contentElement: '#dialog-form-register',
              parent: angular.element(document.body),
              targetEvent: $event,
              clickOutsideToClose: false
            }).then(function (answer) {

            }, function () {
              
            });
            return;
          }).catch(function(response) {
           return false;
        }).finally(function() {
          hideLoading();
          setTimeout(function() {
              angular.element($event.target).button('reset');
          }, 500);
          return false;
        });
      }

      $scope.showFormLogin = function($event) {
          angular.element('#dialog-form-register').remove();
          angular.element('#dialog-form-login').remove();
          showLoading();
          angular.element($event.target).button('loading');
          if(_class == 'account-login' || _class == 'account-register') {
            window.location = base+'dang-nhap.html';
            return;
          }
          $http({
            method: 'GET',
            url: 'index.php?routing=account/login/showFormLogin',
            dataType: 'HTML',
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function(response){
            let html = response.data;
            let temp = $compile(html)($scope);
            angular.element('body').prepend(temp);
            $mdDialog.show({
              controller: DialogController,
              contentElement: '#dialog-form-login',
              parent: angular.element(document.body),
              targetEvent: $event,
              clickOutsideToClose: false
            }).then(function (answer) {

            }, function () {
              
            });
            return;
          }).catch(function(response) {
           return false;
        }).finally(function() {
          hideLoading();
          setTimeout(function() {
              angular.element($event.target).button('reset');
          }, 500);
          return false;
        });
      }

      $scope.input_telephone = 0;
      $scope.register = function($event, $type = 'validate'){
        showLoading();
        angular.element('.form-register').removeClass('has-error');
        angular.element('.form-group').removeClass('has-error');
        angular.element($event.target).button('loading');
        angular.element('.text-danger').remove();
        $http({
            method: 'POST',
            url: 'index.php?routing=account/register/registerOtp&type='+$type,
            data: angular.element('.form-register').serialize(),
            dataType: 'JSON',
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function(response){
            if(response.data['status'] == true) {
              angular.element('.alert-danger').remove();
              $scope.input_telephone = angular.element('#res_input_telephone').val();
              angular.element('#res_otp').removeClass('hidden');
              angular.element('#body_register').addClass('hidden');
              angular.element('#body_register').addClass('hidden');
              angular.element($event.target).addClass('hidden');
              angular.element('#confirm_otp').removeClass('hidden');
              angular.element('#back_from_register').removeClass('hidden');
              var $toast = toastr['success'](response.data['message']);
              if(response.data['register'] == true) {
                $scope.redirect('index.php?routing=common/home');
              }
            } else {
               angular.element('.alert-danger').remove();
              html = '<div class="alert alert-danger">';
              html += '<i class="fa fa-exclamation-circle"></i>';
              html += response.data['error_warning'];
              html += '<button type="button" class="close"data-dismiss="alert">×</button>';
              html += '</div>';
             // angular.element('#res_input_full_name').parents('.modal-body').before(html);
              for (i in response.data) {
                  if(response.data[i]) {
                    var element = $('#res_input_' + i.replace('error_', ''));
                    $(element).after('<div class="text-danger">' + response.data[i] + '</div>');
                    $(element).parent().addClass('has-error');
                  }
                }
            }
            return;
          }).catch(function(response) {
           return false;
        }).finally(function() {
          setTimeout(function() {
              angular.element($event.target).button('reset');
              hideLoading();
          }, 1000);
          return false;
        });
      }

      $scope.getProfileCustomer = function($event) {
        showLoading();
        $http({
            method: 'GET',
            url: 'index.php?routing=account/account/getProfileCustomer',
            dataType: 'html',
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function(response){
            let html = response.data;
            let temp = $compile(html)($scope);
            angular.element('#append_profile').html(temp);
            $scope.cancelDialog($event);
            return;
          }).catch(function(response) {
           return false;
        }).finally(function() {
          setTimeout(function() {
              angular.element($event.target).button('reset');
              hideLoading();
          }, 1000);
          return false;
        });
      }

      $scope.backFromRegister = function($event) {
        angular.element('#body_register').removeClass('hidden');
        angular.element('#res_otp').addClass('hidden');
        angular.element('#back_from_register').addClass('hidden');
        angular.element('#btn_register').removeClass('hidden');
        angular.element('#confirm_otp').addClass('hidden');
      }

      $scope.login = function($event){
        showLoading();
        angular.element('.form-group').removeClass('has-error');
        angular.element($event.target).button('loading');
        var redirect = angular.element('#login_input_redirect').val();
        $http({
            method: 'POST',
            url: 'index.php?routing=account/login/login',
            data: $.param({
                telephone: angular.element('#login_input_telephone').val(),
                password: angular.element('#login_input_password').val(),
            }),
            dataType: 'JSON',
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function(response){
            if(response.data['status'] == true) {
               angular.element('.alert-danger').remove();
              var $toast = toastr['success'](response.data['message']);
              // $scope.redirect(response.data['redirect']);
              if($(window).width() <= 767) {
                location.reload();
              } else {
                $scope.getProfileCustomer($event);
              }
              
              if(redirect !== '') {
                $scope.redirect(redirect);
              }
            } else {
               angular.element('.alert-danger').remove();
              var $toast = toastr['warning'](response.data['message']);
              // html = '<div class="alert alert-danger">';
              // html += '<i class="fa fa-exclamation-circle"></i>';
              // html += response.data['errror']['warning'];
              // html += '<button type="button" class="close"data-dismiss="alert">×</button>';
              // html += '</div>';
              // angular.element('#login_input_telephone').parents('.modal-body').before(html);
            }
            return;
          }).catch(function(response) {
           return false;
        }).finally(function() {
          setTimeout(function() {
              angular.element($event.target).button('reset');
              hideLoading();
          }, 1000);
          return false;
        });
      }

     

      $scope.zones = [];
      function getZones($event) {
          $scope.zones = zones;
      }

      $scope.zone_id = zone_id;
      $scope.district_id = district_id;
      $scope.type_id  = type_id;
      $scope.price_id = price_id;
      $scope.acreage_id = acreage_id;
      $scope.bedroom = bedroom;
      $scope.price_form = 0;
      $scope.price_to = 0;
      $scope.bedroom = bedroom;
      $scope.bathroom = bathroom;
      $scope.direction_house_id = 0;
      $scope.form_fitler_zone  = function($event) {
        showLoading();
        if($scope.zones.length == 0) {
          getZones($event);
        }
        $mdDialog.show({
          controller: DialogController,
          contentElement: '#form_filter_city',
          parent: angular.element(document.body),
          targetEvent: $event,
          clickOutsideToClose: false
        }).then(function (answer) {

        }, function () {
          
        });

        setTimeout(function() {
          hideLoading();
        }, 700);

      }
      // return $filter('filter')(self.contacts, { id: self.selectedId })[0].lastName;
      $scope.districts = [];
      $scope.zoneChanged = function($event) {
        if($scope.zone_id != 0) {
          $http({
              method: 'GET',
              url: 'index.php?routing=localisation/district/getDistrictCode&zone_id='+$scope.zone_id,
              dataType: 'json',
              headers : {'Content-Type': 'application/x-www-form-urlencoded'},
            }).then(function(response){
               $scope.districts= response.data;
               let zone_name = $filter('filter')($scope.zones, { zone_id: $scope.zone_id })[0].name;
               angular.element('#filter_location_name').html(zone_name);
            }).catch(function(response) {
             return false;
          }).finally(function() {
            angular.element($event.target).button('reset');
            return false;
          });
        } else {
          angular.element('#filter_location_name').html('Toàn quốc');
        }
      }

      $scope.district = '';
      $scope.districtChanged = function($event) {
        var string = '';
        var district_id = '';
        let zone_name = $filter('filter')($scope.zones, { zone_id: $scope.zone_id })[0].name;
        string += zone_name;

        var searchIDs = $(".check-district input:checkbox:checked").map(function(){
           string += ' - '+$filter('filter')($scope.districts, { district_id: $(this).val() })[0].name;
        }).get();

        angular.element('#filter_location_name').html(string);
        //
      }

       $scope.applyDistrict = function($event) {
          var district_id = '';
          var searchIDs = $(".check-district input:checkbox:checked").map(function(){
             district_id += ','+$(this).val();
          }).get();
          let url_filter = '&zone_id='+$scope.zone_id+'&district_id='+district_id+'&header=false';
          filter_district(url_filter);
         let get_url = 'index.php?routing=product/filter/getUrlFilter&zone_id='+$scope.zone_id+'&district_id='+district_id;
         getUrl(get_url);
         $mdDialog.cancel();
      }

      $scope.filter = function($event) {

          showLoading();

          $url = '';

          if($scope.zone_id !== 0) {
            $url += '&zone_id='+$scope.zone_id;
          }

          var district_id = '';
          var searchIDs = $(".check-district input:checkbox:checked").map(function(){
             district_id += ','+$(this).val();
          }).get();

          if(district_id != '') {
            $url += '&district_id='+district_id;
          } else {
             const queryString = parent.window.location.href;
             const urlParams = new URLSearchParams(queryString);
             const page_district_id = urlParams.get('district_id')
             if(page_district_id != null) {
                $url += '&district_id='+page_district_id;
             }
          }


          if($scope.type_id !== 0) {
            $url += '&type_id='+$scope.type_id;
          }


          let filter_from_price = $('#min_price').val();
          let filter_to_price = $('#max_price').val();
          let active_filter_price = $('#active_filter_price').val();
          if(active_filter_price == 1) {
             if(filter_from_price || filter_to_price) {
                $url += '&price_from='+filter_from_price;
                $url += '&price_to='+filter_to_price;
              }
          }
         

          let filter_from_acreage = $('#min_acreage').val();
          let filter_to_acreage = $('#max_acreage').val();
          let active_filter_acreage = $('#active_filter_acreage').val();
          if(active_filter_acreage == 1) {
            if(filter_from_acreage || filter_to_acreage) {
                $url += '&acreage_from='+filter_from_acreage;
                $url += '&acreage_to='+filter_to_acreage;
            }
          }

          if($scope.bedroom !== 0) {
            $url += '&bedroom='+$scope.bedroom;
          }

          if($scope.bathroom !== 0) {
            $url += '&bathroom='+$scope.bathroom;
          }

          if($scope.direction_house_id !== 0) {
            $url += '&direction_house_id='+$scope.direction_house_id;
          }



          if($url) {
            $url += '&header=false';
          }

          if($url) {
            filter_district($url);
            let get_url = 'index.php?routing=product/filter/getUrlFilter'+$url;
            getUrl(get_url);
          } else {
            setTimeout(function() {
              hideLoading();
            }, 400);
          }

      }

      function getUrl($url) {
        $http({
              method: 'GET',
              url: $url,
              dataType: 'json',
              headers : {'Content-Type': 'application/x-www-form-urlencoded'},
            }).then(function(response){
                let url_filter = response.data['url_filter'];
                // parent.window.location.href = url_filter;
                window.history.pushState({ path: url_filter }, '', url_filter);
            }).catch(function(response) {
             return false;
          }).finally(function() {
            return false;
          });
      }

      function filter_district($url = '') {
        showLoading();
        // var local = location.pathname.split("/");
        // var local = parent.window.location.href;
        if($scope.zone_id != 0) {
          var  _url = 'index.php?routing=product/zone'+$url;
        } else {
          var  _url = 'index.php?routing=extension/module/latest'+$url;
        }
        $http({
              method: 'GET',
              url: _url,
              dataType: 'html',
              headers : {'Content-Type': 'application/x-www-form-urlencoded'},
            }).then(function(response){
               var html = response.data;
               var temp = $compile(html)($scope);
              if($scope.zone_id != 0) {
                angular.element('.append_html_filter').html(temp);
              } else {
                angular.element('.append_pagination').html(temp);
              } 
               hideLoading();
              
            }).catch(function(response) {
             return false;
          }).finally(function() {
            hideLoading();
            return false;
          });
      }

      $scope.backDialog = function($event) {
        if($scope.districts.length > 0) {
          $scope.districts = [];
        } else {
          $mdDialog.cancel();
        }
      }


      $scope.filter_types = [];
      function getTypes($event) {
        $http({
              method: 'GET',
              url: 'index.php?routing=product/type/autocomplete',
              dataType: 'json',
              headers : {'Content-Type': 'application/x-www-form-urlencoded'},
            }).then(function(response){
                if(response.data['status'] == true) {
                    $scope.filter_types = response.data['types'];
                }
            }).catch(function(response) {
             return false;
          }).finally(function() {
            return false;
          });
      }
      $scope.form_fitler_types  = function($event) {
        showLoading();
        if($scope.filter_types.length == 0) {
          getTypes($event);
        }
        $mdDialog.show({
          controller: DialogController,
          contentElement: '#form_fitler_types',
          parent: angular.element(document.body),
          targetEvent: $event,
          clickOutsideToClose: false
        }).then(function (answer) {

        }, function () {
          
        });

        setTimeout(function() {
          hideLoading();
        }, 700);
      }


      /// START FILTER PRICE
      $scope.filter_type_name = '';
      $scope.selectedType = function($event, filter_type) {
        angular.element('.filter_option_type li').removeClass('active');
        if(filter_type !== '') {
          angular.element($event.target).parent().addClass('active');
          angular.element('.search-item-type').html('<span>'+filter_type['name']+'</span>');
          $scope.type_id          = filter_type['type_id'];
        }  else {
          angular.element($event.target).parent().addClass('active');
          angular.element('.search-item-type').html('<span>Loại hình</span>');
          $scope.type_id          = 0;
        }
        
        $mdDialog.cancel();
      }
      // End filter type

      // Start filter price
      $scope.filter_prices = [];
      $scope.process_price = 0;
      $scope.filter_price_name = 'Chọn giá';
      $scope.form_fitler_price  = function($event) {
        showLoading();
        if($scope.filter_prices.length == 0) {

             $http({
                method: 'GET',
                url: 'index.php?routing=localisation/price/getPrices',
                headers : {'Content-Type': 'application/x-www-form-urlencoded'},
              }).then(function(response){
                $scope.filter_prices = response.data;
                setTimeout(function() {
                }, 300);
                return;
              }).catch(function(response) {
               return false;
            }).finally(function() {
              return false;
            });
        }
        
        $mdDialog.show({
          controller: DialogController,
          contentElement: '#form_fitler_price',
          parent: angular.element(document.body),
          targetEvent: $event,
          clickOutsideToClose: false
        }).then(function (answer) {

        }, function () {
          
        });

        setTimeout(function() {
          hideLoading();
        }, 700);
      }

      $scope.selectedPrice  = function($event, filter_price) {
        if(filter_price !== '') {
          angular.element($event.target).button('loading');
          angular.element('#filter_price li').removeClass('active');
          angular.element($event.target).addClass('active');
          angular.element($event.target).button('reset');
          $scope.price_id          = filter_price['price_id'];
          $scope.filter_price_name = filter_price['name'];
          $scope.price_form = filter_price['price_from'];
          $scope.price_to = filter_price['price_to'];

          $("#slider-range-price").slider('values',0,filter_price['price_from']);
          $("#slider-range-price").slider('values',1,filter_price['price_to']);
          $('#min_price').val(filter_price['price_from']);
          $('#max_price').val(filter_price['price_to']);

          console.log(filter_price);

          // $('#amount_price').html(`${formatPriceNumber(filter_price['price_from'])} - ${formatPriceNumber(filter_price['price_to'])}`);
          $('#amount_price').html(filter_price['name']);

          angular.element('.search-item-price').html('<span>'+filter_price['name']+'</span>');
        } else {
          angular.element($event.target).parent().addClass('active');
          angular.element('.search-item-price').html('<span>Chọn giá</span>');
          $scope.type_id          = 0;
        }
        // $mdDialog.cancel();
      }

      

      $scope.applyPrice = function($event) {
        $('#active_filter_price').val(1);
       // sets first handle (index 0) to 50
        $mdDialog.cancel();
      }




          // START acreage
      $scope.filter_acreages = [];
      $scope.process_acreage = 0;
      $scope.filter_acreage_name = 'Chọn diện tích';
      $scope.area_from = 0;
      $scope.area_to = 0;
      $scope.form_fitler_acreage  = function($event) {
        if($scope.filter_acreages.length == 0) {

          $http({
                method: 'GET',
                url: 'index.php?routing=localisation/area/getAreas',
                headers : {'Content-Type': 'application/x-www-form-urlencoded'},
              }).then(function(response){
                $scope.filter_acreages = response.data;
                setTimeout(function() {
                }, 300);
                return;
              }).catch(function(response) {
               return false;
            }).finally(function() {
              return false;
            });
        }
        
        $mdDialog.show({
          controller: DialogController,
          contentElement: '#form_fitler_acreage',
          parent: angular.element(document.body),
          targetEvent: $event,
          clickOutsideToClose: false
        }).then(function (answer) {

        }, function () {
          
        });
      }

      $scope.selectedAcreage  = function($event, filter_acreage) {
        if(filter_acreage !== '') {
          angular.element($event.target).button('loading');
          angular.element('#filter_acreage li').removeClass('active');
          angular.element($event.target).addClass('active');
          angular.element($event.target).button('reset');
          let acreage_id             = angular.element($event.target).attr("data-id");
          $scope.acreage_id          = acreage_id;
          angular.element('.search-item-acreage').html('<span>'+filter_acreage['name']+'</span>');
          $scope.area_from = filter_acreage['area_from'];
          $scope.area_to = filter_acreage['area_to'];

          $("#slider-range-acreage").slider('values',0,filter_acreage['area_from']);
          $("#slider-range-acreage").slider('values',1,filter_acreage['area_to']);
          $('#min_acreage').val(filter_acreage['area_from']);
          $('#max_acreage').val(filter_acreage['area_to']);
          $('#amount_acreage').html(`${filter_acreage['area_from']} - ${filter_acreage['area_to']}` + ' m2');

        } else {
          angular.element('.search-item-acreage').html('<span>Chọn diện tích</span>');
        }
        // $mdDialog.cancel();
      }

      $scope.applyAcreage = function($event) {
        $('#active_filter_acreage').val(1);
        $mdDialog.cancel();
      }


      $scope.filter_bedrooms = [];
      $scope.filter_bathrooms = [];
      $scope.filter_direction_houses = [];
      $scope.form_fitler_extension  = function($event) {
        showLoading();

        if($scope.filter_bedrooms.length == 0) {
            $http.get('index.php?routing=localisation/bedroom/getBedrooms').then(function(response) {
                $scope.filter_bedrooms = response.data;
            });
        }

        if($scope.filter_bedrooms.length == 0) {
            $http.get('index.php?routing=localisation/bathroom/getBathrooms').then(function(response) {
                $scope.filter_bathrooms = response.data;
            });
        }

        if($scope.filter_direction_houses.length == 0) {
            $http.get('index.php?routing=localisation/direction_house/getDirectionHouses').then(function(response) {
                $scope.filter_direction_houses = response.data;
            });
        }


        $mdDialog.show({
          controller: DialogController,
          contentElement: '#form_fitler_extension',
          parent: angular.element(document.body),
          targetEvent: $event,
          clickOutsideToClose: false
        }).then(function (answer) {

        }, function () {
          
        });
        setTimeout(function() {
          hideLoading();
        }, 300);
        
      }


      
      $scope.selectedBedroom  = function($event, filter_bedroom) {
        angular.element('#filter_bedroom li').removeClass('active');
        if(filter_bedroom == '') {
          angular.element('.bedroom_0').addClass('active');
          $scope.bedroom = 0;
        } else {
          angular.element('.bedroom_'+filter_bedroom['bedroom_id']).addClass('active');
          $scope.filter_bedroom_name = filter_bedroom['name'];
          $scope.bedroom = filter_bedroom['bedroom'];
        }
      }
      $scope.selectedBathroom  = function($event, filter_bathroom) {
        angular.element('#filter_bathroom li').removeClass('active');
        if(filter_bathroom == '') {
          angular.element('.bathroom_0').addClass('active');
          $scope.bathroom = 0;
        } else {
          angular.element('.bathroom_'+filter_bathroom['bathroom_id']).addClass('active');
          $scope.filter_bathroom_name = filter_bathroom['name'];
          $scope.bathroom = filter_bathroom['bathroom'];
        }
      }

      $scope.applyFilterExtention = function($event) {
        $mdDialog.cancel();
      }

      $scope.selectedDirectionHouse  = function($event, filter_direction_house) {
        angular.element('#filter_direction_house li').removeClass('active');
        if(filter_direction_house == '') {
          angular.element('.direction_house_0').addClass('active');
          $scope.direction_house_id = 0;
        } else {
          angular.element('.direction_house_'+filter_direction_house['direction_house_id']).addClass('active');
          $scope.filter_direction_house_name = filter_direction_house['name'];
          $scope.direction_house_id = filter_direction_house['direction_house_id'];
        }
      }


      $scope.closeModel = function($event, $id = '', $remove = true) {
        showLoading();
        angular.element('#'+$id).hide();
        angular.element('#'+$id).modal('hide');
       
          setTimeout(function() { 
            if($remove == true) {
              angular.element('#'+$id).remove();
            }
            hideLoading();
          }, 300);
       
         
      }
          // END acreage

      // END FILTER PRICE


      // $scope.loadFormLogin = function($event){
      //    angular.element($event.target).button('loading');
      //    angular.element('#register').remove();
      //     $http({
      //       method: 'GET',
      //       url: 'index.php?routing=account/login/loadFormPoupLogin',
      //       dataType: 'html',
      //       headers : {'Content-Type': 'application/x-www-form-urlencoded'},
      //     }).then(function(response){
      //       var html = response.data;
      //       var temp = $compile(html)($scope);
      //       angular.element('#login').remove();
      //       angular.element('body').prepend(temp);
      //       angular.element('#login').modal('show');
      //       return;
      //     }).catch(function(response) {
      //      return false;
      //   }).finally(function() {
      //     angular.element($event.target).button('reset');
      //     return false;
      //   });
      // }

      // $scope.loadFormRegister = function($event){
      //   angular.element('#login').remove();
      //    angular.element($event.target).button('loading');
      //     $http({
      //       method: 'GET',
      //       url: 'index.php?routing=account/register/loadFormPoupRegister',
      //       dataType: 'html',
      //       headers : {'Content-Type': 'application/x-www-form-urlencoded'},
      //     }).then(function(response){
      //       var html = response.data;
      //       var temp = $compile(html)($scope);
      //       angular.element('#register').remove();
      //       angular.element('body').prepend(temp);
      //       angular.element('#register').modal('show');
      //       return;
      //     }).catch(function(response) {
      //      return false;
      //   }).finally(function() {
      //     angular.element($event.target).button('reset');
      //     return false;
      //   });
      // }

      // $scope.login = function($event){
      //   angular.element('.form-group').removeClass('has-error');
      //   angular.element($event.target).children('.icon-loading').removeClass('hidden');
      //   angular.element('.alert-danger').remove();
      //   $http({
      //       method: 'POST',
      //       url: 'index.php?routing=account/login/login_poup',
      //       data: $.param({
      //           email: angular.element('.iiwis-form-login input[name=\'email\']').val(),
      //           password: angular.element('.iiwis-form-login input[name=\'password\']').val(),
      //           redirect: angular.element('#redirect').attr('data-id')
      //       }),
      //       dataType: 'JSON',
      //       headers : {'Content-Type': 'application/x-www-form-urlencoded'},
      //     }).then(function(response){
      //       if(response.data['status'] == true) {
      //         var $toast = toastr['success'](response.data['message']);
      //         $scope.redirect(response.data['redirect']);
      //       } else {
      //         var $toast = toastr['warning'](response.data['message']);
      //         html = '<div class="alert alert-danger">';
      //         html += '<i class="fa fa-exclamation-circle"></i>';
      //         html += response.data['errror']['warning'];
      //         html += '<button type="button" class="close"data-dismiss="alert">×</button>';
      //         html += '</div>';
      //         angular.element('.iiwis-form-login input[name=\'email\']').parents('.form-group').before(html);
      //       }
      //       return;
      //     }).catch(function(response) {
      //      return false;
      //   }).finally(function() {
      //     setTimeout(function() {
      //         angular.element($event.target).children('.icon-loading').addClass('hidden');
      //     }, 1000);
      //     return false;
      //   });
      // }

      

      // $scope.getNotification = function($event){
      //   angular.element($event.target).button('loading');
      //     $http({
      //       method: 'GET',
      //       url: 'index.php?routing=account/account/getNotification',
      //       dataType: 'html',
      //       headers : {'Content-Type': 'application/x-www-form-urlencoded'},
      //     }).then(function(response){
      //       var html = response.data;
      //       angular.element('#model_notification').remove();
      //       angular.element('body').prepend(html);
      //       angular.element('#model_notification').modal('show');
      //       return;
      //     }).catch(function(response) {
      //      return false;
      //   }).finally(function() {
      //     angular.element($event.target).button('reset');
      //     return false;
      //   });
      // }

      // $scope.showMenu = function($event) {
      //   angular.element($event.target).next().toggleClass('show');
      // }

      // $scope.redirect = function($url) {
      //   setTimeout(function() {
      //    window.location = $url;
      //   }, 1000);
      // }



      $scope.categorys = {};
      $scope.text_select_type = 'Các loại đất';
      $scope.searchDropdowProductType = function($event){
        angular.element('.loading_city_country').show();
        angular.element('.show_content_search').hide();
         angular.element('.search-cate .select-custom #lblCurrCate .fa').toggleClass('fa-angle-up fa-angle-down');
        $http({
            method: 'GET',
            url: 'index.php?routing=product/category/getAllCategory',
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function(response){
            var res = response.data;
            $scope.categorys = res.categories;
            angular.element('.search-cate .advance-select-options').toggleClass('hidden show');
            setTimeout(function() {
                angular.element('.loading_city_country').hide();
                angular.element('.show_content_search').show();
            }, 300);
            return;
          }).catch(function(response) {
           return false;
        }).finally(function() {
          return false;
        });
      }


       $scope.selectType = function($category) {
        $scope.text_select_type = $category.name;
        angular.element('#category_id').val($category.category_id);
        angular.element('.search-cate .advance-select-options').toggleClass('hidden show');
      }

      // START SEARCH IN CAGEGORY
      $scope.searchCategory = function($event){
        angular.element('.loading_city_country').show();
        angular.element('.show_content_search').hide();
        $http({
            method: 'GET',
            url: 'index.php?routing=product/category/getAllCategory',
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function(response){
            var res = response.data;
            $scope.categorys = res.categories;
             angular.element('.custom-dropbox-cate').toggleClass('show hidden');
            setTimeout(function() {
                angular.element('.loading_city_country').hide();
                angular.element('.show_content_search').show();
            }, 300);
            return;
          }).catch(function(response) {
           return false;
        }).finally(function() {
          return false;
        });
      }
      // END SEARCH IN CAGEGORY

      $scope.selectTypeCategory = function($category) {
        $scope.text_result_search_category = $category.name;
        angular.element('#category_id').val($category.category_id);
       
      }

     

      $scope.selectM2 = function($val) {
        angular.element('.select-text-content_m2').text($val);
        $scope.searchDropdowM2();
      }

      $scope.selectPrice = function($val) {
        angular.element('.select-text-content_price').text($val);
        $scope.searchDropdowPrice();
      }

      $scope.searchDropdowM2 = function($event){
         angular.element('#divAreaOptionsM2').toggleClass('hidden show');
       
      }

      $scope.searchDropdowPrice = function($event){
         angular.element('#divAreaOptionsPrice').toggleClass('hidden show');
       
      }

      $scope.searchMain = function($event) {
        angular.element($event.target).button('loading');

        setTimeout(function() {
            angular.element($event.target).button('reset');
        }, 300);
      }

      $scope.submitFormBooking = function($event) {
        angular.element($event.target).button('loading');
        showLoading();

        setTimeout(function() {
            angular.element($event.target).button('reset');
            $('#tour_booking_modal').modal('hide');
            $('#success_modal').modal('show');
            hideLoading();
        }, 300);
      }

      $scope.modalShare = function($event) {
        $('#modal_share').modal('show');
      }


      $scope.submitFormReport = function($event) {
        angular.element($event.target).button('loading');
        angular.element('.form-group').removeClass('error');
        angular.element('.message').html('');
        showLoading();

        $http({
            method: 'POST',
            url: 'index.php?routing=product/product/report',
            data: angular.element('#tour_report_form').serialize(),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function(response){
            var data = response.data;
            if(data.status == false) {
              for (i in data) {
                  if(data[i]) {
                    let input = i.replace('error_', '');
                    let id = '#input-' + input.replace('_', '-');
                    console.log(id);
                    let element = $(id); 
                    // $(element).before('<div class="text-danger">' + data[i] + '</div>');
                    $(element).parents('.form-group').addClass('error');
                    $(element).parent().next('.message').html('<div class="text-danger">' + data[i] + '</div>');
                  }
              }
              if(data.error_info) {
                var $toast = toastr['warning'](data.error_info);
              }
            }
            if(data.status == true) {
              $('#btn_show_modal_report').prop('disabled', true);
              $('input[name=\'booking_fullname\']').val('');
              $('input[name=\'booking_telephone\']').val('');
              $('input[name=\'booking_email\']').val('');
              $('textarea[name=\'booking_content\']').val('');
              $('.description_message').html(data.message);
              $('#tour_report_modal').modal('hide');
              $('#success_modal').modal('show');
            }
            return;
          }).catch(function(response) {
           return false;
        }).finally(function() {
          angular.element($event.target).button('reset');
          hideLoading();
          return false;
        });

        // setTimeout(function() {
        //     angular.element($event.target).button('reset');
        //     $('#tour_report_modal').modal('hide');
        //     $('#success_modal').modal('show');
        //     hideLoading();
        // }, 300);
      }

});



// app.controller('categoryCtrl', function($scope, $http, $compile, $element, $timeout) {
      
//       $scope.zones = [];
//       $scope.searchTermZone = '';
      
//       $scope.loadZone = function() {
       
//         $http({
//             method: 'GET',
//             url: 'index.php?routing=localisation/country/country&country_id=230',
//             headers : {'Content-Type': 'application/x-www-form-urlencoded'},
//           }).then(function(response){
//             var res = response.data;
           
//                 $scope.zones = res;
             
            
//           }).catch(function(response) {
//            return false;
//         }).finally(function() {
//           return false;
//         });
        
        
//       }


//       $scope.clearSearchZone = function () {
//         $scope.searchTermZone = '';
//       };



//       $scope.districts = [];
//       $scope.searchTermDistrict = '';

//       $scope.loadDistrict = function() {
//         $http({
//             method: 'GET',
//             url: 'index.php?routing=localisation/district/getDistrictCode&zone_id='+$scope.zone_id,
//             headers : {'Content-Type': 'application/x-www-form-urlencoded'},
//           }).then(function(response){
//             var res = response.data;
//             $scope.districts = res;
//             return;
//           }).catch(function(response) {
//            return false;
//         }).finally(function() {
//           return false;
//         });
        
//       }
     
//       $scope.clearSearchDistrict = function () {
//         $scope.searchTermDistrict = '';
//       };





//       // The md-select directive eats keydown events for some quick select
//       // logic. Since we have a search input here, we don't need that logic.
//       $element.find('input').on('keydown', function (ev) {
//         ev.stopPropagation();
//       });



    
// });


