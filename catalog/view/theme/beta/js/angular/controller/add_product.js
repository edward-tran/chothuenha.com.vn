

app.controller('addProductCtrl', function($scope,$http, $timeout, $compile) {
    $scope.type = null;
    $scope.types = null;

    $scope.loadTypes = function() {
      // Use timeout to simulate a 650ms request.
        $scope.types =  json_types;
    };

    $scope.zone = null;
    $scope.zones = null;

    // $scope.loadZones = function($event) {
    //  $http({
   //          method: 'GET',
   //          url: 'index.php?routing=localisation/zone/getZoneByCountryId&country_id='+country_id,
   //          dataType: 'JSON',
   //          headers : {'Content-Type': 'application/x-www-form-urlencoded'},
   //        }).then(function(response){
   //         $scope.zones = response.data;
   //          return;
   //        }).catch(function(response) {
   //         return false;
   //      }).finally(function() {
   //         return false;
   //      });
    // }

     $scope.fruitNames = ['Sổ đỏ/ Sổ hồng', 'Hợp đồng mua bán', 'Đang chờ sổ'];
     $scope.from_date = new Date();

     $scope.minDate = new Date(
       $scope.from_date.getFullYear(),
       $scope.from_date.getMonth() - 1,
       $scope.from_date.getDate()
     );

     $scope.maxDate = new Date(
       $scope.from_date.getFullYear(),
       $scope.from_date.getMonth() + 1,
       $scope.from_date.getDate()
     );

     var month = $scope.from_date.getMonth()+1;

     $scope.from_date_full = $scope.from_date.getDate()+'-'+month+'-'+$scope.from_date.getFullYear();
     $scope.checkSchedule = function($event) {
        var month = $scope.from_date.getMonth()+1;
        $scope.from_date_full = $scope.from_date.getDate()+'-'+month+'-'+$scope.from_date.getFullYear();
        setTimeout(function() {
          $('select[name=\'post_type_id\']').trigger('change');
        }, 1000);

     }



     function getFormData() {
        let dataForm = new FormData();
        let $form = $('.form_add_product');
      
        let $inputImages = $form.find('input[name^="images"]');
        if (!$inputImages.length) {
              $inputImages = $form.find('input[name^="photos"]')
        }

        for (let file of $inputImages.prop('files')) {
            // dataForm.append("userfile", file);
            // dataForm.append("files[]", file);
            dataForm.append("files[]", file);
               // dataForm.append('file', file);
        } 
        dataForm.append("type_id", angular.element('select[name=\'type_id\']').val());
        dataForm.append("direction_house_id", angular.element('select[name=\'direction_house_id\']').val());
        dataForm.append("interiors", angular.element('input[name=\'interiors\']').val());
        dataForm.append("direction_balcony_id", angular.element('select[name=\'direction_balcony_id\']').val());
        dataForm.append("post_type_id",  angular.element('select[name=\'post_type_id\']').val());
        dataForm.append("date_post_id",  angular.element('select[name=\'date_post_id\']').val());
        dataForm.append("from_date",  angular.element('input[name=\'from_date\']').val());
        dataForm.append("price",  angular.element('#input-price').val());
        dataForm.append("video",  angular.element('#input-video').val());
        dataForm.append("zone_id",  angular.element('#input-zone-id').val());
        dataForm.append("zone_name",  angular.element('#input-zone-id option:selected').text());
        dataForm.append("ward_id",  angular.element('#input-ward-id').val());
        dataForm.append("ward_name",  angular.element('#input-ward-id option:selected').text());
        dataForm.append("district_id",  angular.element('#input-district-id').val());
        dataForm.append("district_name",  angular.element('#input-district-id option:selected').text());
        dataForm.append("street_id",  angular.element('#input-street-id').val());
        dataForm.append("street_name",  angular.element('#input-street-id option:selected').text());
        dataForm.append("location",  angular.element('#input-location').val());
        dataForm.append("name",  angular.element('#input-name').val());
        dataForm.append("description",  angular.element('#input-description').val());
        dataForm.append("customer_name",  angular.element('#input-customer-name').val());
        dataForm.append("customer_telephone",  angular.element('#input-customer-telephone').val());
        dataForm.append("customer_address",  angular.element('#input-customer-address').val());
        dataForm.append("customer_email",  angular.element('#input-customer-email').val());
        dataForm.append("acreage",  angular.element('#input-acreage').val());
        dataForm.append("bedroom",  angular.element('#input-bedroom').val());
        dataForm.append("bathroom",  angular.element('#input-bathroom').val());
        dataForm.append("floors",  angular.element('#input-floors').val());
        dataForm.append("image",  $('input[name=\'temp_images[]\']').val());
        dataForm.append("facade",  $('input[name=\'facade\']').val());
        dataForm.append("large_road",  $('input[name=\'large_road\']').val());
        return dataForm;
     }


     $scope.reviewProduct  = function($event, $type = '') {
        showLoading();
        angular.element('.show_warning').html('');
        angular.element('.text-danger').remove();
        angular.element('.form-control').removeClass('has-error');
        angular.element('div').removeClass('has-error');
        angular.element('.form-group').removeClass('has-error');
        angular.element($event.target).button('loading');
        let data_form = getFormData();
        $.ajax({
          url: 'index.php?routing=account/product/reviewProduct',
          type: "post",
          data: data_form,
          enctype: "multipart/form-data",
          contentType: false,
          processData: false,
          beforeSend: function (e) {
           
          },
          success: function (response) {

            let data = response;
              if(data['status'] == true) {
                if($type == 'confirm') {
                  $scope.showFormConfirm($event);
                } else {
                  let html = data['html'];
                  let temp = $compile(html)($scope);
                  angular.element('body').prepend(temp);
                  $('#ReactModalPortal').modal({backdrop: 'static', keyboard: false})  
                }
                
              } else {
                for (i in data) {
                  if(data[i]) {
                    let input = i.replace('error_', '');
                    let id = '#input-' + input.replace('_', '-');
                    let element = $(id); 
                    $(element).before('<div class="text-danger">' + data[i] + '</div>');
                    $(element).parent().addClass('has-error');
                  }
                }
                goToByScroll('has-error', 'class');            
            }
            angular.element($event.target).button('reset');
            hideLoading();
            return;

             
          },
          error: function (response) {
             angular.element($event.target).button('reset');
             hideLoading();
          },
        });
        return;
     }

     $scope.nextAddProduct = function($event) {
        $scope.reviewProduct($event, 'confirm');
     }

     $scope.showFormConfirm = function($event) {
        showLoading();
        angular.element('.show_warning').html('');
        angular.element('.text-danger').remove();
        angular.element('.form-control').removeClass('has-error');
        angular.element('.form-group').removeClass('has-error');
        angular.element($event.target).button('loading');
        let data_form = getFormData();
        $.ajax({
          url: 'index.php?routing=account/product/getFormConfirmProduct',
          type: "post",
          data: data_form,
          enctype: "multipart/form-data",
          contentType: false,
          processData: false,
          beforeSend: function (e) {
           
          },
          success: function (response) {

             var data = response;
             if(data['status'] == true) {
                $('.confirm_price_service').html(data['price_service']);
                $('.confirm_service_name').html(data['service_name']);
                $('.confirm_date_post').html(data['date_post']);
                $('.confirm_from_date').html(data['from_date']);
                $('.confirm_to_date').html(data['to_date']);
                $('.confirm_price_vat').html(data['price_vat']);
                $('.confirm_price_finnal').html(data['price_finnal']);
                $('.note_post').html(data['note_post']);


                $('#ReactModalConfirmProduct').modal('show');  
             }
            return;
            angular.element($event.target).button('reset');
            hideLoading();
            return;
          },
          error: function (response) {
             angular.element($event.target).button('reset');
             hideLoading();
          },
        });
     }

     $scope.acceptAddProduct = function($event) {
        showLoading();
        angular.element('.show_warning').html('');
        angular.element('.text-danger').remove();
        angular.element('.form-control').removeClass('has-error');
        angular.element('.form-group').removeClass('has-error');
        angular.element($event.target).button('loading');
        let data_form = getFormData();

        $.ajax({
          url: 'index.php?routing=account/product/acceptAddProduct',
          type: "post",
          data: data_form,
          enctype: "multipart/form-data",
          contentType: false,
          processData: false,
          beforeSend: function (e) {
           
          },
          success: function (response) {
            if(response['status'] == true) {
               angular.element('#ReactModalConfirmProduct').hide();
               angular.element('#ReactModalConfirmProduct').modal('hide');
               angular.element('#success_modal').modal('show');

               if(response['redirect']) {
                  // window.history.pushState({ path: response['redirect'] }, '', response['redirect']);
                  window.history.pushState({ path: 'index.php?routing=account/payment_post?product_id'+response['product_id'] }, '', 'index.php?routing=account/payment_post?product_id'+response['redirect']);
                  showLoading();
                  setTimeout(function() { 
                    location.reload();
                    hideLoading();
                  }, 1000);
                }
                hideLoading();
            } else {
              hideLoading();
            }
            angular.element($event.target).button('reset');
            return;

             
          },
          error: function (response) {
             angular.element($event.target).button('reset');
             hideLoading();
          },
        });
     }


});


