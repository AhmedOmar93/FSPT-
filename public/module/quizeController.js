myApp.controller('all_quizzes_controller',function($scope){
	active_tape(5);
});

myApp.controller('single_quize_controller',function($scope,$routeParams){
 $scope.templateUrl =path_prefix+"/quiz/show/"+$routeParams.gId+"/"+$routeParams.qId+"/"+$routeParams.model;
//alert("single worked");
active_tape(5);
});

myApp.controller('addQuizeController',function($scope){
	active_tape(5);
});

myApp.controller('addQuestionQuiz',function($scope){
	active_tape(5);
});

