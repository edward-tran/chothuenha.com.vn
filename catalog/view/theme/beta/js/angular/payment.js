



app.controller('paymentCtrl', function($scope, $http) {
      angular.element('.show_message').html('');
      $scope.paymentNganluong = function($event){
         angular.element($event.target).button('loading');
         angular.element('.show_warning').html('');
         angular.element('.text-danger').remove();
         $('td').removeClass('has-error');
          $http({
            method: 'POST',
            data: angular.element('.form-horizontal').serialize(),
            dataType: 'JSON',
            url: 'index.php?routing=payment_custom/nganluong/payment',
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function(response){
             var res = response.data;
             if(res.error_warning) {
              var $toast = toastr['error'](res.error_warning);
              if(res.error_bankcode) {
                  angular.element('.show_message').html($scope.showMessageError(res.error_bankcode));
              }
              for (i in res) {
                if(res[i]) {
                  var element = $('#input-' + i.replace('error_', ''));
                  $(element).after('<div class="text-danger">' + res[i] + '</div>');
                  $(element).parent().addClass('has-error');
                }
              }
             } else {
                if(res.success == true) {
                  var $toast = toastr['success'](res.message);
                  setTimeout(function() {
                    window.location = res.url_redirect;
                  }, 1000);
                } else {
                  var $toast = toastr['error'](res.message);
                  angular.element('.show_message').html($scope.showMessageError(res.message));
                }
             }
            return;
          }).catch(function(response) {
           return false;
        }).finally(function() {
          angular.element($event.target).button('reset');
          return false;
        });
      }

      $scope.showMessageError = function($message) {
          html = '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
          html +='<strong>'+$message+'</strong>';
          html +='<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
          html +='<span aria-hidden="true">&times;</span>';
          html +='</button>';
          html +='</div>';
          return html;
      }

 

});
