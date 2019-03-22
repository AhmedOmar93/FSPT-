FriendsListApp.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };
});


FriendsListApp.controller('ChatController', ['$http','$sce','ChatService','$scope','$rootScope','$window', function($http,$sce,$ChatService,$scope,$rootScope,$window) {

var selef = this;
selef.chat =[];
var openRooms = [];

var unSeenMsgArray = [];
 
     $window.app.BrainSocket.Event.listen('chat.send',function(msg){
        if(msg.client.data['user_id'] != User['id'] ){

             var receiveNewMsg = "<div  class='direct-chat-msg'>"
            + " <div class='direct-chat-info clearfix'>"
            + " <span class='direct-chat-name pull-left'>"+msg.client.data['first_name']+" "+msg.client.data['last_name']+"</span>"
            + " <span class='direct-chat-timestamp pull-right'>23 Jan 2:00 pm</span>"
            + " </div><!-- /.direct-chat-info -->"
            + " <img class='direct-chat-img' src='"+path_prefix+(msg.client.data['pic'] != null && msg.client.data['pic'] != '' ? 'images/'+msg.client.data['pic'] : 'dist/img/no_user.jpg' )+"' alt='message user image' /><!-- /.direct-chat-img -->"
            + " <div class='direct-chat-text'>"
            + msg.client.data['body']
            + " </div><!-- /.direct-chat-text -->"
            + " </div><!-- /.direct-chat-msg -->";
             $("#chat-room-"+msg.client.data['chat_room']).append(receiveNewMsg);  

              var elem = document.getElementById('chat-room-'+msg.client.data['chat_room']);
              elem.scrollTop = elem.scrollHeight;
            // console.log($("#header-room-"+msg.client.data['chat_room']).hasClass("open")); 
             if(! $("#header-room-"+msg.client.data['chat_room']).hasClass("open") ){
                
                if($("#unseen-room-"+msg.client.data['chat_room']).attr("data-countMsg") != "" ){
                    var count = parseInt($("#unseen-room-"+msg.client.data['chat_room']).attr("data-countMsg") );
                     count++;
                    $("#unseen-room-"+msg.client.data['chat_room']).attr("data-countMsg",count.toString());  
                     $("#unseen-room-"+msg.client.data['chat_room']).text(count.toString()); 
              
                } else{
                   $("#unseen-room-"+msg.client.data['chat_room']).attr("data-countMsg","1");
                   $("#unseen-room-"+msg.client.data['chat_room']).text("1");

            }
          }             
        }
    });

$rootScope.$on('User:offline', function (event,offline_user) {
  for (var i = 0; i < selef.chat.length; i++) {
     if(selef.chat[i].friend_id == offline_user){
       $scope.$apply(function () {
        selef.chat[i].online = false;
     });
    }
  }
});

$rootScope.$on('User:online', function (event, online_user) {
  for (var i = 0; i < selef.chat.length; i++) {
     if(selef.chat[i].friend_id == online_user){
       $scope.$apply(function () {
        selef.chat[i].online = true;
     });
    }
  }
});

$rootScope.$on('rootScope:broadcast', function (event, friendData) {
//console.log(friendData); // 'Broadcast!'
var obj ={};
var room_id = $ChatService.listChat();
if (openRooms.length > 0) {
  if (openRooms.indexOf(room_id) != -1) {
    return;
  }
}
openRooms.push(room_id);
var data = [];
var chatBody = [];
var now = new Date ();
var date_time = now.format('d M Y h:i:s');
var body =  angular.element( document.getElementById('chat-msg-body') );

obj['room_id'] = room_id;
obj['first_name'] = friendData.first_name;
obj['last_name'] = friendData.last_name;
obj['friend_id'] = friendData.friend_id;
    
  

$http.get('/api/get/roomMessages/' + room_id).then(function(response) {
data = response.data;
for (var i = 0; i < data.length; i++) {
  if (data[i]['id'] == User['id'] ) {
    var sender = $sce.trustAsHtml("<div id='"+data[i]['msgId']+"' class='direct-chat-msg right'>"
+"  <div class='direct-chat-info clearfix'>"
+"    <span class='direct-chat-name pull-right'>"+User['first_name']+" "+User['last_name']+"</span>"
+"    <span class='direct-chat-timestamp pull-left'>"+data[i]['created_at']+"</span>"
+"  </div><!-- /.direct-chat-info -->"
+"  <img class='direct-chat-img' src='"+path_prefix+(data[i]['pic'] != null && data[i]['pic'] != ''? 'images/'+data[i]['pic'] : 'dist/img/no_user.jpg' )+"' alt='message user image' /><!-- /.direct-chat-img -->"
+"  <div class='direct-chat-text'>"
+   data[i]['body']
+"  </div><!-- /.direct-chat-text -->"
+"</div><!-- /.direct-chat-msg -->   ");
        chatBody.push(sender);
  }else{
    var receiver = $sce.trustAsHtml("<div id='"+data[i]['msgId']+"' class='direct-chat-msg'>"
+ " <div class='direct-chat-info clearfix'>"
+ " <span class='direct-chat-name pull-left'>"+data[i]['first_name']+" "+data[i]['last_name']+"</span>"
+ " <span class='direct-chat-timestamp pull-right'>23 Jan 2:00 pm</span>"
+ " </div><!-- /.direct-chat-info -->"
+ " <img class='direct-chat-img' src='"+path_prefix+(data[i]['pic'] != null && data[i]['pic'] != ''? 'images/'+data[i]['pic'] : 'dist/img/no_user.jpg' )+"' alt='message user image' /><!-- /.direct-chat-img -->"
+ " <div class='direct-chat-text'>"
+ data[i]['body']
+ " </div><!-- /.direct-chat-text -->"
+ " </div><!-- /.direct-chat-msg -->");
    chatBody.push(receiver);

  }
}
obj['chatBody'] = chatBody;
}, function(errResponse) {
console.error('Error while fetching notes');
  });

var count = 0;
var message = '';
obj['message'] = message;


var SendMessage =  function ( ) {
  if(this.message == "" || this.message == null || this.message == undefined)
    return;
count--;
     var newMsg = "<div id='"+count+"' class='direct-chat-msg right'>"
+"  <div class='direct-chat-info clearfix'>"
+"    <span class='direct-chat-name pull-right'>"+User['first_name']+" "+User['last_name']+"</span>"
+"    <span class='direct-chat-timestamp pull-left'>"+date_time+"</span>"
+"  </div><!-- /.direct-chat-info -->"
+"  <img class='direct-chat-img' src='"+(User['pic'] != null && User['pic'] != '' ? 'images/'+User['pic'] : 'dist/img/no_user.jpg' )+"' alt='message user image' /><!-- /.direct-chat-img -->"
+"  <div class='direct-chat-text'>"
+  this.message
+"  </div><!-- /.direct-chat-text -->"
+"</div><!-- /.direct-chat-msg -->   ";

$("#chat-room-"+this.room_id).append(newMsg);
body.append(newMsg);

var elem = document.getElementById('chat-room-'+this.room_id);
elem.scrollTop = elem.scrollHeight;

$("#input-room-"+this.room_id).val("");

$window.app.BrainSocket.message('chat.send',
      {
      'body':this.message ,
      'user_id':User['id'],
      'chat_room':this.room_id,
      'create_at':date_time,
      'first_name':User['first_name'],
      'last_name':User['last_name'],
      'pic':User['pic'],
      'friend_id':this.friend_id
      }

    );
     
var msgData = {
  'msg':this.message ,
  'user_id':User['id'],
  'room_id':this.room_id
};


$http.post('/api/post/setMessage',msgData).
    success(function(data, status, headers, config) {
      }).
    error(function(data, status, headers, config) {
      console.error(data);
      });
};


var removeChat = function  (event) {
   //console.log($(event.target).parents(".chat-bottom2"));
 //  $(event.target).parents(".chat-bottom2")[0].remove();
 
   var roomIndex = openRooms.indexOf(this.room_id);
   var chatIndex  = selef.chat.indexOf(this);
   openRooms.splice(roomIndex,1);
   selef.chat.splice(chatIndex,1);

 }


obj['SendMessage'] = SendMessage;

obj['removeChat'] = removeChat;

obj['unSeenMsg'] = 0;

var d = $ChatService.getAllOnlineUsers();
if ($ChatService.checkUserOnline(friendData.friend_id)) {
obj['online'] = true;
console.log(obj);
}

selef.chat.push(obj);

//console.log(selef.chat);

    
});

}]);



