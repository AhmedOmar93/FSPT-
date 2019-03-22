var projectApp=angular.module('projectApp',[]);

projectApp.controller('createProject',function($scope,$http){
	$scope.add=function(createProjectForm){

		 if(!createProjectForm.$error.required){
		 	//$http.post();
		 	$http.post('http://localhost:8000/createProject/',$scope.project).success(function(){
		 		alert("sucessfully Created");
		 		$scope.project="";
		 	}).error(function(data){
		 		alert(data);
		 		console.log(data);

		 	});


		 }else{
			alert("Check Valid Data  ,  All Fields are Required",'error');
		 }

		return false;
	}
});





