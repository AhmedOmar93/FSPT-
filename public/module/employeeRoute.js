var myApp=angular.module('myApp',['ngRoute','ngSanitize']);
var calendar_loaded=false;

var is_admin=false;

if(is_member==1){
    is_admin=true;
}


//window.location="#/questions";
//global variable
myApp.factory('Data',function(){
  return {
    path_prefix:path_prefix,
    image_path:path_prefix+'images'
  };
});

if(is_member==1){
myApp.config(function($routeProvider) {
  $routeProvider.
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
            
        }).when('/complaints',{
            templateUrl:path_prefix+'/SuggestionAndComplaints/'+group.id,
            controller:'complaintsDetails'

        }).when('/calendar',{
            templateUrl:path_prefix+'partials/empty.html',
            controller:'calendarDetails'

        });

});
    console.log("admmmmmmmmmmmmin");
}else{
    myApp.config(function ($routeProvider) {
        $routeProvider.
                when('/details', {
                    templateUrl: path_prefix + 'partials/empty.html',
                    controller: 'groupDetails'

                }).
                when('/complaints', {
                    templateUrl: path_prefix + '/SuggestionAndComplaints/' + group.id,
                    controller: 'complaintsDetails'

                }).when('/calendar', {
                    templateUrl: path_prefix + 'partials/empty.html',
                    controller: 'calendarDetails'
                }).otherwise({
                    redirectTo:'/details'
                });
    });

}



$(document).ready(function() {
    angular.bootstrap(document.getElementById('chat-staff'), ['FriendsListApp']);
});


myApp.run(function($rootScope, $templateCache) {
   $rootScope.$on('$viewContentLoaded', function() {
      $templateCache.removeAll();
   });
});


//multi tap handle
function active_tape(tap_number){

  $(".showAdmin").hide();
  $("#eventDetailsContainer").hide();

  for(var i=0;i<5;i++){
  $('#menu li').eq(i).removeClass('active');
//  $("#tape_contents div").eq(i).hide();
  }

  $('#menu li').eq(tap_number-1).addClass('active');
 // $("#tape_contents div:nth-child(1)").addClass('active');

    $("#announcements").removeClass("active");
    $("#calendarContainer").removeClass("active");
    $("#details").removeClass("active");
    $("#group_content").removeClass("active");
    if (is_member == 1) {
        if (tap_number == 2 || tap_number == 4) {
            $("#group_content").addClass("active");
        } else if (tap_number == 1) {
            $("#announcements").addClass("active");
        } else if (tap_number == 3) {
            $("#calendarContainer").addClass("active");
        } else if (tap_number == 5) {
            $("#details").addClass("active");
        }
    }else {
        if (tap_number == 1) {
            $("#group_content").addClass("active");
        } else if (tap_number == 2) {
            $("#calendarContainer").addClass("active");
        } else if (tap_number == 3) {
            $("#details").addClass("active");
        }
    }
  
}



myApp.controller('groupDetails',function($scope){
  if(is_member==1){
       active_tape(5);
  }else{
    active_tape(3);
  } 
});

myApp.controller('announcementsDetails',function($scope){
  active_tape(1);
});

myApp.controller('complaintsDetails',function($scope){
    
    if(is_member==1){
       active_tape(2);
  }else{
       active_tape(1);
  } 
    
});



