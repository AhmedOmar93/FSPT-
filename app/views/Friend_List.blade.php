<style type=text/css>
.frindList {
	position: fixed;
    top: 70px;
    right: 0px;
    background: #fff;
    border-radius: 5px 0px 0px 5px;
    padding: 10px 15px;
    font-size: 16px;
    z-index: 99999;
    cursor: pointer;
    color: #3c8dbc;
    box-shadow: 0 1px 3px rgba(0;0;0;0.1)
    }

.friendListTable {
    padding: 10px;
    position: fixed;
    top: 70px;
    right: -250px;
    background: #fff;
    border: 0px solid #ddd;
    width: 250px;
    z-index: 99999;
    box-shadow: 0 1px 3px rgba(0;0;0;0.1);

    }

    .friendListScroll{
    overflow-y:scroll;
    height: 300px;
    }
    
    .sidebar-form2 {
  border: 0px solid #fff;  /* */
  margin: 10px 10px;
}
.sidebar-form2 input[type="text"],
.sidebar-form2 .btn {
  box-shadow: none;
  background-color: #F0F8FF; /* */
  border: 0px solid transparent;
  height: 35px;
}
.sidebar-form2 input[type="text"] {
  color: #666;
}
.sidebar-form2 input[type="text"]:focus,
.sidebar-form2 input[type="text"]:focus + .input-group-btn .btn {
  background-color: #F0F8FF;
  color: #666;
}
.sidebar-form2 input[type="text"]:focus + .input-group-btn .btn {
  border-left-color: #fff;
}
.sidebar-form2 .btn {
  color: #999;
}

.sidebar-friend-list {
  list-style: none;
  margin: 0;
  padding: 0;
}
.sidebar-friend-list > li {
  position: relative;
  margin: 0;
  padding: 0;
}
.sidebar-friend-list > li > a {
  padding: 12px 5px 12px 15px;
  display: block;
}
.sidebar-friend-list > li > a > .fa,
.sidebar-friend-list > li > a > .glyphicon,
.sidebar-friend-list > li > a > .ion {
  width: 20px;
}
.sidebar-friend-list > li .label,
.sidebar-friend-list > li .badge {
  margin-top: 3px;
  margin-right: 5px;
}
.sidebar-friend-list li.header {
  padding: 10px 25px 10px 15px;
  font-size: 12px;
}
.sidebar-friend-list li > a > .fa-angle-left {
  width: auto;
  height: auto;
  padding: 0;
  margin-right: 10px;
  margin-top: 3px;
}

.sidebar-friend-list > li:hover  {
  color: #F0F8FF;
  background: #F0F8FF;
}

.online {
     padding: 6px;
     font-size:10px;
}

</style>


<div id="btn_list" class="no-print frindList open">
	<i class='fa  fa-group'></i>
</div>

<div class="no-print friendListTable " id="FriendsLis" >
     <h4 class='text-light-blue' style='margin: 0 0 5px 0; border-bottom: 1px solid #ddd; padding-bottom: 15px;'>Friends List</h4>
     <section ng-controller="FriendsListController as ListCtlr">
        <div class="sidebar-form2">
            <div class="input-group ">
              <input type="text" name="q" class="form-control" ng-model="search" placeholder="Search..."/>
              <span class="input-group-btn">
                <button  name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </div>
          <ul class="sidebar-friend-list friendListScroll">
            
            <li ng-repeat="friend in ListCtlr.friendsData | filter:search" ng-click="ListCtlr.AddChat(friend.user_id,friend.room_id,friend.first_name,friend.last_name);">
            <a ng-switch on="friend.profile_picture" id="friend-list-@{{friend.user_id}}" ng-init="friend.online=false" >
            <img width="25" ng-switch-when="null" ng-src="{{URL::asset('dist/img/no_user.jpg')}}" class="user-image" alt="User Image">
            <img width="25" ng-switch-default ng-src="{{URL::asset('images')}}/@{{friend.profile_picture}}" class="user-image" alt="User Image">
             &nbsp &nbsp &nbsp<span>@{{friend.first_name}}</span> <span>@{{friend.last_name}}</span> 
            <i class="fa fa-circle text-success pull-right online" ng-show="friend.online" ></i>
            </a>
            </li>
              
          </ul><!-- /.sidebar-menu -->
        </section>
</div>

<script type="text/javascript">
$("#btn_list").click(function () {
    if (!$(this).hasClass("open")) {
      $(this).animate({"right": "250px"});
      $("#FriendsLis").animate({"right": "0"});
      $(this).addClass("open");
    } else {
      $(this).animate({"right": "0"});
      $("#FriendsLis").animate({"right": "-250px"});
      $(this).removeClass("open");
    }
  });

</script>