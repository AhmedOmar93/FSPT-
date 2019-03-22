var path_prefix='http://192.168.1.25:8000/';

var WebSocket_Path = 'ws://192.168.1.25:8081';

/*******  php artisan brainsocket:start --port=8081 **********/

var app = {};

app.BrainSocket = new BrainSocket(
			new WebSocket(WebSocket_Path),
			new BrainSocketPubSub()
	);

	

$(document).ready(function (){

setTimeout(function(){
 app.BrainSocket.message('app.open',{
	'user_id':User.id,
	'do':20505
 });
},2000);


});


//determine Theme Based On User Choice

var themeName="";
var className="";
var color="";
var colorName=User.color.toLowerCase();


    switch(User.color){
        case "Green":
            themeName="skin-green";
            className="success";
            color="#00a65a";
            break;
        case "Red":
            themeName="skin-red";
            color="#d73925";
            className='danger';
            break;
        case "Blue":
            themeName="skin-blue";
            className='primary';
            color="#367fa9";
            break;
        case "Purple":
            className="default";
            themeName="skin-purple";
            color="#605ca8";
            break;
        case "Yellow":
            className='warning';
            themeName="skin-yellow";
            color="#f39c12";
            break;
        case "Black":
            className="default";
            themeName="skin-black";
            color="#000";
            break;
        default:
            themeName="skin-blue";
    }

   function alert(message){
        $.notify(message,"success",{ position:"bottom right"});
   }
   function alert(message,myClass){
        if(!myClass) myClass="success";
        $.notify(message,myClass,{ position:"bottom right"});
   }

  function ConfirmRequest (id) {
    $("#request-"+id).remove();
    $.ajax({
      method: "POST",
      url: "api/activeFriend/"+id,
      success: function(result){
             $('#myscript').remove();
            $.getScript( "../module/FriendsListController.js" )
              .done(function( script, textStatus ) {
                console.log( textStatus );
              })
              .fail(function( jqxhr, settings, exception ) {
                 console.log( "Triggered ajaxError handler." );
            });


        }
    });
}   

function DeleteRequest (id) {
    $("#request-"+id).remove();
    $.ajax({
      method: "GET",
      url: "/cancelRequest/"+id
    });
}

function DisplayNotification ( ) {
$.ajax({
      method: "GET",
      url: path_prefix + "api/get/notification/",
      success: function(result){
        var notifyBody = "";
        for (var i = 0; i < result.length; i++) {
            var now = new Date (result[i].creation_date);
            var date_time = now.format('d M Y h:i a');
             notifyBody += "<li><a href='#'>"+
            "<div><i class='fa fa-users text-aqua'></i> <span style='white-space:normal;'>"+
             result[i].message +"</span>"+ 
            "<br /><span style='color:#CCC;font-size:12px' class='pull-right'>"+
            date_time+"</span></div></a></li>";
            
        }   
        $("#menu-notification-data").html(notifyBody);
        
        }
    });
}

function DisplayNotificationCount ( ) {
    $.ajax({
      method: "GET",
      url: path_prefix + "api/get/notification/count",
      success: function(result){
        var notifiyCount = result[0].notification_count;

        if (notifiyCount == 0) 
        {
            $("#notifiyCount-data").css("display","none");
            $("#notifiy-header-data").css("display","none");
            
        }
        else{
          $("#notifiyCount-data").html(notifiyCount);
          $("#notifiyCount2-data").html(notifiyCount);
          $("#notifiyCount-data").css("display","block");
          $("#notifiy-header-data").css("display","block");
         }
        }
    });
    
}

function updateNotification (argument) {
    $.ajax({
      method: "GET",
      url: path_prefix + "api/get/notification/update",
      success: function(result){
        $("#notifiyCount-data").css("display","none");
        $("#notifiy-header-data").css("display","none");

        }
    });
}
setInterval(function (){
    DisplayNotification();
    DisplayNotificationCount();
},10000);