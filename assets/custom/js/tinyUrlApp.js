var app = angular.module("tinyUrlApp",[]);

app.controller("tinyUrlCtrl", ['$scope', '$http', function ($scope, $http) {

	$scope.frm = {};

	$scope.initGetPopular = function ()
	{
		 $scope.url = "Welcome/fetchPopularUrls";
		 $http.get($scope.url).then(function(response) {
			 if(response.data.length > 0) {
			 	$scope.tbl_records = response.data;
			 } else {
			 	$scope.tbl_records = null;
			 }
        });
	}

	// Call to generate tiny URL
    $scope.getTinyUrl = function ()
    {
		//$scope.showLoader = true;
		$scope.frm.is_ok = '';
        $scope.url = "Welcome/fetchTinyUrl";
        $http.post($scope.url, {'long_url' : $scope.frm.long_url}).then(function(response) {
			 if(response.data.is_error) {
			 	$scope.frm.is_ok = 'failure';
			 	$scope.frm.err_msg = response.data.status_msg;
			 } else {
			 	$scope.frm.is_ok = 'success';
			 	$scope.frm.tiny_url = response.data.short_code;
			 	$scope.initGetPopular();
			 }
        });
    }
}]);