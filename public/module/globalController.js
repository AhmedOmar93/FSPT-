var myApp=angular.module('myApp',['ngRoute','ngSanitize']);

var all_Question=[]; // list which have all question inside group
var all_material=[]; //list contain all materials inside group
var load_material=false;
var load_question=false;
var questionPosition=0;

//window.location="#/questions";
//global variable
myApp.factory('Data',function(){
  return {
    path_prefix:path_prefix,
    image_path:path_prefix+'images'
  };
});

myApp.config(function($routeProvider) {
  $routeProvider.
  when('/questions', {
    templateUrl: path_prefix+'partials/question/list.html',
    controller: 'questionListController'
  }).
  when('/questionDetails/:q_id',{
    templateUrl:path_prefix+'partials/question/details.html',
    controller:'questionDetailsController'

  }).
  when('/notfound',{
      templateUrl:path_prefix+'partials/question/notFound.html',
      controller:'notFoundController'

  }).
  when('/addQuestion',{
    templateUrl:path_prefix+'partials/question/addQuestion.html',
    controller:'addQuestion'
  }).
  when('userQuestions',{
    templateUrl:path_prefix+'partials/question/userQuestion.html',
    controller:'userQuestionController'
  }).
  
  when('/quizzes',{
    templateUrl:path_prefix+'api/quize/getAll/'+group.id,
    controller:'all_quizzes_controller'
  }).
  when('/quiz/show/:gId/:qId/:model',{
    templateUrl:path_prefix+'api/quize/one',
    controller:'single_quize_controller'
  }).
      when('/surveys',{
          templateUrl:path_prefix+'api/survey/getAll/'+group.id,
          controller:'all_surveys_controller'
      }).
      when('/VoteList', {
        templateUrl:path_prefix +'partials/vote/list.html',
        controller: 'listController'
    }).
        when('/details/:itemId',{
            templateUrl:path_prefix+'partials/vote/details.html',
            controller:'detailsController'

        }).
        when('/notfound',{
            templateUrl:path_prefix+'partials/vote/notFound.html',
            controller:'notFoundController'

        }).
        when('/addVote',{
            templateUrl:path_prefix+'partials/vote/addVote.html',
            controller:'addVoteController'
        }).when('/details',{
            templateUrl:path_prefix+'partials/empty.html',
            controller:'groupDetails'
            
        }).when('/announcements',{
            templateUrl:path_prefix+'partials/empty.html',
            controller:'announcementsDetails'
            
        }).when('/assignments',{
            templateUrl:path_prefix+'/Assignments/'+group.id,
            controller:'assignmentsDetails'
            
        }).when('/filter/assignments/:targetId',{
            templateUrl:path_prefix+'/Assignment/filter',
            controller:'assignmentsDetails2'
            
        }).when('/addQuize',{
          templateUrl:path_prefix+'/quiz/create/'+group.id,
          controller:'addQuizeController'
        }).when('/addQuestionToQuiz',{
          templateUrl:path_prefix+'/quiz/create/question/'+group.id,
          controller:'addQuestionQuiz'

        }).when('/material',{
          templateUrl:path_prefix+"partials/material/all.html",
          controller:'materialController'
        });

});



//multi tap handle
function active_tape(tap_number){

  for(var i=0;i<7;i++){
  $('#menu li').eq(i).removeClass('active');
//  $("#tape_contents div").eq(i).hide();
  }

  $('#menu li').eq(tap_number-1).addClass('active');
 // $("#tape_contents div:nth-child(1)").addClass('active');

    $("#announcements").removeClass("active");
    $("#details").removeClass("active");
    $("#group_content").removeClass("active");

  if(tap_number==2 || tap_number==4 || tap_number==5 || tap_number==6 || tap_number==3){
    $("#group_content").addClass("active");
  }else if(tap_number==1){
    $("#announcements").addClass("active");
  }else if(tap_number==7){
    $("#details").addClass("active");
  }
  
}


myApp.controller('groupDetails',function($scope){
  active_tape(7);
});

myApp.controller('announcementsDetails',function($scope){
  active_tape(1);
});

myApp.controller('assignmentsDetails',function($scope){
  active_tape(3);

});

myApp.controller('assignmentsDetails2',function($scope,$routeParams){

active_tape(3);
$scope.templateUrl=path_prefix+'/Assignments/category/'+group.id+'/'+$routeParams.targetId;

});

myApp.run(function($rootScope, $templateCache) {
   $rootScope.$on('$viewContentLoaded', function() {
      $templateCache.removeAll();
   });
});




$(document).ready(function() {
    angular.bootstrap(document.getElementById('chat-staff'), ['FriendsListApp']);
    //angular.bootstrap(document.getElementById('createProjectBox'), ['projectApp']);

});

