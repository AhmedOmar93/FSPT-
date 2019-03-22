
var myApp = angular.module('myApp', ['ngRoute']);

 	myApp.config(['$routeProvider', function($routeProvider) {
 	//$locationProvider.html5Mode(true);
 	//$locationProvider.hashPrefix('!');
	$routeProvider.when('/', {
	//templateUrl: "/"
	})

		/*
	.when('/profile', {
	templateUrl: '/edite/profile',
	controller: 'ProfileEditController'
	})
		.when('/login-sn', {
			templateUrl: '/login'
		})

*/
		.otherwise({redirectTo: '/'});
}]);

/*
myApp.controller('ProfileEditController', ['$scope', '$http', function($scope, $http) {


var userId = "1";
    $http.get('/api/get/profile/'+ userId ).then(function(response) {
		$scope.mydata = response.data;
	
		}, function(errResponse) {
			console.error('Error while fetching data');
		});


    $scope.btn_EditProfile = function(){

		$http.post('/api/sent/profile',$scope.mydata ).
		success(function(data, status, headers, config) {
		
		  //$scope.result=data;
				//console.log(data);
				$scope.mydata = data;
	
		  }).
		error(function(data, status, headers, config) {
				console.error('Error while fetching data');
		  });

	  };
	  
}]);
*/