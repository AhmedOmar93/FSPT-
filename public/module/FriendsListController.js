var FriendsListApp =angular.module('FriendsListApp',['ngSanitize','ng.deviceDetector']);

FriendsListApp.factory('ChatService', ['$rootScope','$http',function($rootScope,$http) {
	var chatRooms = new Queue();
	var onlineUsers = [];
	var connId = 0;
	var connSession = "";
	//onlineUsers.push(2);
	
	return {

		listChat: function() {
			return chatRooms.dequeue();
		},
		addChat: function(item) {
			chatRooms.enqueue(item);
		},
		addOnlineUser:function(user){
			onlineUsers.push(user);
		},
		addAllOnlineUsers:function  (users) {
			onlineUsers = angular.copy(users);
		},
		removeOnlineUser:function (user) {
			var userIndex = onlineUsers.indexOf(user);
			if(userIndex != -1){
				onlineUsers.splice(userIndex,1);
			}
		},
		checkUserOnline:function(user){
			var userIndex = onlineUsers.indexOf(user);
			if(userIndex != -1){
				return true;
			}
			return false;	
		},
		getAllOnlineUsers:function(){
			return onlineUsers;
		},
		setConnId:function (conn) {
			connId = conn;
		},
		getConnId:function() {
			return connId ;
		},
		setConnSession:function (conn) {
			connSession = conn;
		},
		getConnSession:function() {
			return connSession ;
		}

	};

}]);




FriendsListApp.controller('FriendsListController', ['$http','$sce','ChatService','$scope','$rootScope','$window', function($http,$sce,$ChatService,$scope,$rootScope,$window) {
var selef = this;
selef.friendsData = [];
$http.get('/api/get/getAllFriends').then(function(response) {
	selef.friendsData = response.data; 
	}, function(errResponse) {
	console.error('Error while fetching notes');
  });

$http.get('/api/get/getAllOnlineFriends').then(function(response) {
	var onlineUsers = response.data;
	$ChatService.addAllOnlineUsers(onlineUsers);
	for (var i = 0; i < selef.friendsData.length; i++) {
		for (var j = 0; j < onlineUsers.length; j++) {
			if(selef.friendsData[i].user_id == onlineUsers[j].USERS)
				selef.friendsData[i].online = true; 
		}
	}
	}, function(errResponse) {
		console.error('Error while fetching data');
  	});


selef.AddChat = function  (friend_id,room_id,first_name,last_name) {
 $ChatService.addChat(room_id);
 var friendData = {
 	'first_name':first_name,
 	'last_name':last_name,
 	'friend_id':friend_id
 };
 $rootScope.$broadcast('rootScope:broadcast',friendData);

};
	
$window.app.BrainSocket.Event.listen('app.close',function(data){
//console.log( data);
	for (var i = 0; i < selef.friendsData.length; i++) {
		if(selef.friendsData[i].user_id == data.server.data['user_id'])
		{
			if ($ChatService.checkUserOnline(selef.friendsData[i].user_id)) {
				$ChatService.removeOnlineUser(selef.friendsData[i].user_id);
			}
 			$rootScope.$broadcast('User:offline',data.server.data['user_id']);
			$scope.$apply(function () {
				selef.friendsData[i].online = false;					
			});
		}
	};

});


$window.app.BrainSocket.Event.listen('app.open',function(data){
	//console.log( data);
	for (var i = 0; i < selef.friendsData.length; i++) {
		if(selef.friendsData[i].user_id == data.client.data['user_id'])
		{
			if (!$ChatService.checkUserOnline(selef.friendsData[i].user_id)) {
				$ChatService.addOnlineUser(selef.friendsData[i].user_id);
			}
			$rootScope.$broadcast('User:online',data.client.data['user_id']);
			$scope.$apply(function () {
				selef.friendsData[i].online = true;	
			});
		}
		if (data.client.data.user_id == User.id && data.client.data.do == 20505) {
			$ChatService.setConnId(data.client.port);
			$ChatService.setConnSession(data.client.session);
		}
	}
});


}]);

FriendsListApp.controller('ConnInfoController',['deviceDetector','$http','$window','ChatService',function (deviceDetector,$http,$window,ChatService) {
	

setTimeout(function(){


	var  os  = deviceDetector.os;
	var  browser = deviceDetector.browser;
	var  obj = deviceDetector.raw;
	var  device = deviceDetector.device;

	//console.log(os);
	//console.log(obj);
	var os_version = "";
	
	for(var version in obj.os_version){
		if (obj.os_version[version] == true) {
			os_version = version.toString();
			break;
		}
	}

	if (device == undefined || device == "unknown") {
		device = "Desktob";
	}

 
	var data = {
	'os':os,
	'browser':browser,
	'os_version':os_version,
	'device':device,
	'connId':ChatService.getConnId(),
	'conn_sess':ChatService.getConnSession()
	};

if (ChatService.getConnId() != 0) {

	$http.post('/api/post/connection',data).
    success(function(data, status, headers, config) {
    //	console.log("conn saved");
      }).
    error(function(data, status, headers, config) {
      console.log(data);
      });
 }

},5000);


}]);

$(document).ready(function() {
    //angular.bootstrap(document.getElementById('chat-staff'), ['FriendsListApp']);
    angular.bootstrap(document.getElementById('createProjectBox'), ['projectApp']);

});

