var myApp=angular.module('snApp',['ngRoute','ngSanitize']);

var path_prefix='http://localhost:8000/';

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
  when('/calendar/edit', {
    templateUrl: path_prefix+'/user/20110056',
    controller: 'calendarController'
  });
  
});

myApp.controller('calendarController',function($scope){
 // console.log("worked");
});




$(document).ready(function() {
    angular.bootstrap(document.getElementById('chat-staff'), ['FriendsListApp']);
});
