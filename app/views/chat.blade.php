<style type="text/css">
.chat-bottom {
position: fixed;
bottom: 0;
}
.chat-bottom2{
    bottom: -332px;
}
.online2 {
     padding: 4px;
     
}

</style>

<div id="myApp2" class="chat-bottom" ng-controller="ChatController as chatCtlr">

<div class="col-md-3 pull-right chat-bottom2"  ng-repeat=" chatData in chatCtlr.chat track by  $index">
<!-- Construct the box with style you want. Here we are using box-danger -->
<!-- Then add the class direct-chat and choose the direct-chat-* contexual class -->
<!-- The contextual class should match the box, so we are using direct-chat-danger -->
<div class="box box-primary direct-chat direct-chat-primary" data-room-id="chatData.room_id" >
  <div class="box-header with-border" id="header-room-@{{chatData.room_id}}" >
    <i class="fa fa-circle text-success pull-left online2" style="font-size:12px;" ng-show="chatData.online" ></i>
    <h3 class="box-title">@{{chatData.first_name}} @{{chatData.last_name}}</h3>
    <div class="openChat box-tools" style="width:100px;height:25px;" id="open-room-@{{chatData.room_id}}"></div>
    <div class="box-tools pull-right">
      <span ng-show="true" data-toggle="tooltip" data-countMsg="" id="unseen-room-@{{chatData.room_id}}" title="3 New Messages" class='badge bg-blue'></span>

      <!-- In box-tools add this button if you intend to use the contacts pane -->
      <button class="btn btn-box-tool removeChat" ng-click="chatData.removeChat($event)" data-room-id="@{{chatData.room_id}}" ><i class="fa fa-times"></i></button>
    </div>
  </div><!-- /.box-header -->
  <div class="box-body" id="body-room-@{{chatData.room_id}}">
    <!-- Conversations are loaded here -->
    <div class="direct-chat-messages" id="chat-room-@{{chatData.room_id}}" >

      <!-- Message. Default to the left -->
	<div ng-repeat="msg in chatData.chatBody">
      <div ng-bind-html="msg"></div>
	</div>

      <!-- Message to the right -->


    </div><!--/.direct-chat-messages-->

    <!-- Contacts are loaded here -->
    <div class="direct-chat-contacts">
      <ul class='contacts-list'>
        <li>
          <a href='#'>
            <img class='contacts-list-img' src='../dist/img/user1-128x128.jpg'/>
            <div class='contacts-list-info'>
              <span class='contacts-list-name'>
                Count Dracula
                <small class='contacts-list-date pull-right'>2/28/2015</small>
              </span>
              <span class='contacts-list-msg'>How have you been? I was...</span>
            </div><!-- /.contacts-list-info -->
          </a>
        </li><!-- End Contact Item -->                      
      </ul><!-- /.contatcts-list -->
    </div><!-- /.direct-chat-pane -->


  </div><!-- /.box-body -->
  <div class="box-footer">
    <div class="input-group">
      <input type="text" name="message" id="input-room-@{{chatData.room_id}}" ng-enter="chatData.SendMessage()" id="chat-message" ng-model="chatData.message" placeholder="Type Message ..." class="form-control"/>
      <span class="input-group-btn">
        <button type="button"  class="btn btn-primary btn-flat" ng-click="chatData.SendMessage()">Send</button>
      </span>
    </div>
  </div><!-- /.box-footer-->
</div><!--/.direct-chat -->
     </div>


</div>



<script type="text/javascript">
$(document).on("click", ".openChat", function(e){
  e.preventDefault();
      var id = ($(this).attr("id")).split("-");
      var room_id = id[2];
  if (!$("#header-room-"+room_id).hasClass("open")) {
      $("#header-room-"+room_id).parentsUntil(".chat-bottom2").animate({"bottom": "300"});
      $("#header-room-"+room_id).addClass("open");
    var elem = document.getElementById('chat-room-'+room_id);
    elem.scrollTop = elem.scrollHeight;
      $("#unseen-room-"+room_id).text(""); 
      $("#unseen-room-"+room_id).attr("data-countMsg","");


    } else {
      $("#header-room-"+room_id).parentsUntil(".chat-bottom2").animate({"bottom": "-1px"});
      $("#header-room-"+room_id).removeClass("open");
    }

});

/*
$(document).on("click", ".removeChat", function(e){
  console.log($(this).attr("data-room-id"));
  $(this).parentsUntil(".chat-bottom2").parent().remove();
   var chatIndex = openRooms.indexOf($(this).attr("data-room-id"));
   openRooms.splice(chatIndex,1);
});*/

</script>