



app.controller('postCtrl', function($scope, $http, $compile) {



// ADD PROJECT
$scope.addProject = function($event) {
    angular.element('.show_warning').html('');
    angular.element('.text-danger').remove();
    angular.element($event.target).button('loading');
    $http({
        method: 'POST',
        url: 'index.php?routing=project/project/addProject',
        data: angular.element('#form-project').serialize(),
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    })
    .success(function(response){
      if(response.error_warning) {
         var $toast = toastr['error'](response['error_warning']);
         for (i in response) {
          if(response[i]) {
            var element = $('#input-' + i.replace('error_', ''));
            $(element).after('<div class="text-danger">' + response[i] + '</div>');
            $(element).parent().addClass('has-error');
           // $('html, body').animate({ scrollTop: 0 }, 'slow');
          }
        }
      }

    }).catch(function(response) {
        
    })
    .finally(function() {
       angular.element($event.target).button('reset');
    })
}

/// END PROJECT

  $scope.changeProductType = function($event){
    
      $('#input-category_id').select2({
         allowClear: true,
         theme: "bootstrap",
         cache: false,
         ajax: { 
         url: 'index.php?routing=product/category/getAllCategoryByProductTypeId&product_type_id='+$scope.product_type_id,
         dataType: 'json',
         delay: 250,
         data: function (params) {
          return {
            filter_name: params.term, // search term
            page: params.page
          };
        },
       processResults: function (data, page) {
         return {
            results: $.map(data, function (item) {
              return {
                text: item.name,
                id: item.category_id
              }
            })
         };
       }
      },
    });
    
  }



});