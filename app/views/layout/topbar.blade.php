<!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="/" class="logo"><b>FCI-H</b></a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <!-- Menu toggle button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success"></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header"></li>
                  <li>
                    <!-- inner menu: contains the messages -->
                    <ul class="menu">
                    <?php 
                      $chatObj = new ChatController();
                      foreach($chatObj->getAllFrindsMsg() as $request){?>
                      <li><!-- start message -->
                        <a href="#">
                          <div class="pull-left">
                            <!-- User Image -->
                            <img src="{{URL::asset('images/'.$request->pic)}}" class="img-circle" alt="User Image"/>
                          </div>
                          <!-- Message title and timestamp -->
                          <h4>                            
                            {{$request->first_name}} {{$request->last_name}}
                            <small><i class="fa fa-clock-o"></i> <?php echo  $chatObj->getDateFormate($request->created_at); ?></small>
                          </h4>
                          <!-- The message -->
                          <p>{{$request->body}}</p>
                        </a>
                      </li><!-- end message --> 
                      <?php } ?>                     
                    </ul><!-- /.menu -->
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li><!-- /.messages-menu -->

              <!-- Notifications Menu -->
              <li class="dropdown notifications-menu">
                <!-- Menu toggle button -->

                <?php $notifyObj = new NotificationController();
                      $notifuCount = $notifyObj->get_notification_count();
                ?>
                <a href="" class="dropdown-toggle" onclick="updateNotification();" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning" id="notifiyCount-data"> </span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header" id="notifiy-header-data">You have <span id="notifiyCount2-data"></span> notifications</li>
                  
                  <li>
                    <!-- Inner Menu: contains the notifications -->
                    <ul class="menu" id="menu-notification-data">
                       <!-- start notification -->
                  
                      <!-- end notification --> 
                                   
                    </ul>
                  </li>
                  <li class="footer"><a href="#">View all</a></li>
                </ul>
              </li>



              <!-- Tasks Menu -->
              <li class="dropdown messages-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="glyphicon glyphicon-user"></i>
                  <span class="label label-danger"></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Friend Requests</li>
                  <li>

                  
                    <!-- Inner menu: contains the tasks -->
                    <ul class="menu">
                     
                     <?php 
                      $obj = new FriendsListController();
                      foreach($obj->getFriendsRequest() as $request){?>
                      <li id="request-{{$request['friendsListId']}}"><!-- Task item -->
                          <a >
                          <div class="pull-left">
                            <!-- User Image -->
                            <img src="{{URL::asset('images/'.$request['profile_picture'])}}" class="img-circle" alt="User Image"/>
                          </div>
                          <!-- Message title and timestamp -->
                          <h4>                            
                            {{$request['first_name']}} {{$request['last_name']}}
                          </h4>
                          <div class="btn-group pull-right" style="padding-top: 5px;">
                          <button class="btn  btn-primary btn-xs" onclick="ConfirmRequest({{$request['friendsListId']}});" >Confirm</button> 
                          <button class="btn  btn-primary btn-xs" onclick="DeleteRequest({{$request['friendsListId']}});">Delete Request</button>
                          </div>

                          <!-- The message -->
                       
                        </a>
                      </li><!-- end task item --> 
                      <?php } ?>                     
                    </ul>
                  </li>
                  <li class="footer">
                    <a href="#">View all Requests</a>
                  </li>
                </ul>
              </li>
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <img src="<?php echo ProfileController::get_pp(); ?>" class="user-image" alt="User Image"/>
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs"><?php echo ProfileController::get_name(); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="user-header">
                    <img src="<?php echo ProfileController::get_pp(); ?>" class="img-circle" alt="User Image" />
                    <p>
                      <?php echo ProfileController::get_name(); ?> - Web Developer
                      <small>Member since Nov. 2012</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="/calendar/edit">Calendar</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#" onclick='javascript:introJs().start();'>Guide</a>
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="/user/{{Auth::user()->user_code}}" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="/account/sign-out" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>