'use strict';
/** 
  * controllers for Angular File Upload
*/
app.controller('UploadCtrl', ['$scope', 'FileUploader',
function ($scope, FileUploader) {
    var uploaderImages = $scope.uploaderImages = new FileUploader({
        url: 'upload.php'
    });

    // FILTERS

    uploaderImages.filters.push({
        name: 'imageFilter',
        fn: function (item/*{File|FileLikeObject}*/, options) {
            var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
            return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
        }
    });

    // CALLBACKS

    function convertBase64(file){
      return new Promise((resolve, reject) => {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(file);

        fileReader.onload = () => {
          resolve(fileReader.result);
        };

        fileReader.onerror = (error) => {
          reject(error);
        };
      });
    };

    function getBaseUrl ($file)  {
        var file = $file;
        var reader = new FileReader();
        var baseString;
        reader.onloadend = function () {
            baseString = reader.result;
            angular.element('#input-image').val(baseString);
        };
        reader.readAsDataURL(file);

    }

    uploaderImages.onWhenAddingFileFailed = function (item/*{File|FileLikeObject}*/, filter, options) {
        //console.info('onWhenAddingFileFailed', item, filter, options);
    };
    uploaderImages.onAfterAddingFile = function (fileItem) {
        //console.info('onAfterAddingFile', fileItem);
    };

    $scope.files = [];

    uploaderImages.onAfterAddingAll = function (addedFileItems) {
        
        // const file = addedFileItems[0]['_file'];
        // const base64 =  getBaseUrl(file);
        
        // console.log(addedFileItems);
        // angular.element.each(addedFileItems, function(index, value) {
        //     var html = '';
        //     html +='<input type="text" name="image[]" value="">';
        //     console.log(addedFileItems[index]['_file'])
        // });
        
        
    };
    uploaderImages.onBeforeUploadItem = function (item) {
        //console.info('onBeforeUploadItem', item);
    };
    uploaderImages.onProgressItem = function (fileItem, progress) {
        //console.info('onProgressItem', fileItem, progress);
    };
    uploaderImages.onProgressAll = function (progress) {
        //console.info('onProgressAll', progress);
    };
    uploaderImages.onSuccessItem = function (fileItem, response, status, headers) {
        //console.info('onSuccessItem', fileItem, response, status, headers);
    };
    uploaderImages.onErrorItem = function (fileItem, response, status, headers) {
        //console.info('onErrorItem', fileItem, response, status, headers);
    };
    uploaderImages.onCancelItem = function (fileItem, response, status, headers) {
        //console.info('onCancelItem', fileItem, response, status, headers);
    };
    uploaderImages.onCompleteItem = function (fileItem, response, status, headers) {
        //console.info('onCompleteItem', fileItem, response, status, headers);
    };
    uploaderImages.onCompleteAll = function () {
        //console.info('onCompleteAll');
    };

    // console.info('uploader', uploaderImages);
}]);
