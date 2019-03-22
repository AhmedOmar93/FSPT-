@extends('layout.mainTheme')
@section('content')
    @if($user->id == Auth::user()->id)

  {{--*/ $class = ProfileController::get_class(); /*--}}

    <div class="box box-solid">
        <div id='profile_banner' style=''>
        <div class="row" >
            <div class="col-md-5">
                <div>
                    <center>
                            <img onclick='view(this)'  src="{{ProfileController::get_PP()}}" class='img-circle'  style='margin-top:5px;height:120px;width:120px;border:3px solid #fff;cursor:pointer;'>
                            <h2>{{ProfileController::get_name()}}</h2>
                            <h4>{{$user->email}}</h4>
                    </center>
                </div>
            </div>
            <div class="col-md-5">
                <center>
                    <h4>{{ $user->aboutMe }}</h4>
                    <h4> WebSite : {{$user->website}} </h4>
                    <h4> {{$user->department}}</h4>
                    <h4> Live In : {{$user->city}} - {{$user->country}}</h4>

                </center>
            
            </div>
        </div>
        </div>
        
        <div class="row" >
            
            <div class="col-md-7" style='margin:0px;'>
                <div class="box-body">
                    <div class="box-group" id="accordion">

                        <div class="panel  box box-{{ProfileController::get_class()}}">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#addAnnouncement">
                                        Add Announcement
                                    </a>
                                </h4>
                            </div>
                            <div id="addAnnouncement" class="panel-collapse collapse in">
                                <form id="form-add-announcement" action="#">       
                                    <div class="box-body off_white">
                                          <div id='announcementtitle'>
                                                    <dt>Title:</dt><dd><input type="text" name="title" id="title" placeholder="Add Title to Announcement" class="form-control" required title="Announcement Title"/></dd>
                                                </div>
                                                <div>
                                                    <dt>Announce It:</dt><dd><textarea id="addannounce" name="addannounce" placeholder="What you have to say?" required title="Official Announcement" class="form-control"></textarea></dd>
                                             
                                                    <label>to:</label>
                                                    <select class='form-control' name="levelselection" id="levelselection">
                                                        <option selected="selected">ChooseLevel</option>
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                    </select>
                                                </div>
                                    </div>
                                    <div class='box-footer'>
                                        <input type="submit" value="Add Announcement" id="submbtn" class="btn pull-right btn-{{ProfileController::get_class()}}">
                                    </div>  
                                </form>
                            </div>
                        </div>
                        <div class="nav-tabs-custom box box-{{ProfileController::get_class()}}">
                                            <ul class="nav nav-tabs ">
                                                <li class="active "><a href="#" data-toggle="tab" id="announcement_link">My Announcements</a></li>
                                                <li><a href="#" data-toggle="tab" id="level_link">Level Announcements</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div  class="tab-pane active " id='announcements'><div id="announcement_container"></div></div>      
                                                <div  class="tab-pane" id='announcementlevel'><div id='announcement_level'></div></div>
                                            </div>
                                        </div>  
                    </div>
                </div>
            </div>
            <div class="col-md-5" style='margin:0px;'>
                <div class="box-body">
                    <div class='box'>
                        <div class="box-header with-border">
                            <h4 class="box-title">Basic Information</h4>
                        </div>
                        <div class="box-body off_white">
                                <ul class='btn-group-vertical'>
                                    <li>Name :{{$user->first_name}} {{$user->middle_name}} {{$user->last_name}}</li>
                                    <li>Birth date : {{$user->birth_date}}</li>
                                    <li>phone : {{$user->phone}}</li>
                                    <li>level : {{$user->level}}</li>
                                    <li>address : {{$user->street}}-{{$user->city}}-{{$user->country}}</li>
                                </ul>
                          <div class="btn-group-vertical" role="group" aria-label="...">
                            
                         </div>          
                        </div>
                    </div>
                </div> 
                <div class="box-body">
                    <div class='box'>
                        <div class="box-header with-border">
                            <h4 class="box-title">Courses</h4>
                        </div>
                        <div class="box-body off_white">
                                <ul class='menu'>
                                    @foreach($groups as $group)
                                        <li>{{$group->name}}</li>
                                        @endforeach

                                </ul>
                        </div>
                    </div>
                </div> 
                <div class="box-body">
                    <div class='box'>
                        <div class="box-header with-border">
                            <h4 class="box-title">Friends</h4>
                        </div>
                        <div class="box-body off_white">
                                <table class='menu' cell-spacing='1'>

                                            <tr>
                                                <td><img src="#" class='pp'></td>
                                                <td><h4>Ahmed Adel</h4></td>
                                            </tr>


                                </table>
                        </div>
                    </div>
                </div> 

            </div>
        </div>
    </div>

<div class="box box-solid box-{{$class}}">
    <div class="box-header">
        <div class="box-title">Edite Profile</div>
    </div>
    <div class="box-body">
        <div class='row'>
            <div class="col-md-4">
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <div class="panel box box-{{ProfileController::get_class()}}">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                                        Change Picture
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse in">
                                <div class="box-body">
                                    <div id="image_preview"><img width="200" height="200" id="previewing" src="../../images/{{$user->profile_picture}}"  /></div>
                                    <hr id="line">
                                    <form id="uploadimage" action="{{  URL::route('image-upload') }}" method="post" enctype="multipart/form-data">
                                        <div id="selectImage">
                                            Select Your Image :<br/>
                                            <input type="file" class='form-control' name="file" id="file" required />
                                            <input type="submit" class='btn btn-{{$class}} btn-block' value="Upload" class="submit" />
                                        </div>
                                        {{ Form::token() }}
                                    </form>
                                    <div id="message"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <div class="panel box box-{{ProfileController::get_class()}}">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        Theme Colors
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="box-body">
                                    <a class = 'changeColor btn btn-block btn-danger' href="/change/user/color/Red">Red</a>
                                    <a class = 'changeColor btn btn-block btn-primary' href="/change/user/color/Blue">Blue</a>
                                    <a class = 'changeColor btn btn-block btn-success' href="/change/user/color/Green">Green</a>
                                    <a class = 'changeColor btn btn-block bg-purple' href="/change/user/color/Purple">Purple</a>
                                    <a class = 'changeColor btn btn-block btn-warning' href="/change/user/color/Yellow">Yellow</a>
                                    
                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <div class="panel box box-{{ProfileController::get_class()}}">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                                        Change personel Information
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse3" class="panel-collapse collapse in">
                                <div class="box-body off_white">

                                    <dl class="dl-horizontal">
                                        <form action="{{  URL::route('user-edit-profile') }}" method="post" id="editProfileForm">

                                            <div id="first_name">
                                                <dt>change First name:</dt>
                                                <dd><input class="form-control" type="text" name="first_name" value="{{Auth::user()->first_name}}"></dd>
                                            </div>
                                            <div id="middle_name">
                                                <dt>change Middle name:</dt>
                                                <dd><input class="form-control" type="text" name="middle_name" value="{{Auth::user()->middle_name}}"></dd>
                                            </div>
                                            <div id="last_name">
                                                <dt>change Last name:</dt>
                                                <dd> <input class="form-control" type="text" name="last_name" value="{{Auth::user()->last_name}}"></dd>
                                            </div>

                                            <div id="user_name">
                                                <dt>change username:</dt>
                                                <dd><input class="form-control" type="text" name="user_name" value="{{Auth::user()->user_name}}"></dd>
                                            </div>

                                            <div id="birth_date">
                                                <dt>change Birth Date:</dt>
                                                <dd><input class="form-control" type="date"   name="birth_date" value="{{Auth::user()->birth_date}}"></dd>
                                            </div>

                                            <div id="street">
                                                <dt>change street:</dt>
                                                <dd><input class="form-control" type="text"  name="street" value="{{Auth::user()->street}}"></dd>
                                            </div>

                                            <div id="city">
                                                <dt>change city:</dt>
                                                <dd> <input class="form-control" type="text"   name="city" value="{{Auth::user()->city}}"></dd>
                                            </div>

                                            <div id="country">
                                                <dt>change country:</dt>
                                                <dd><input class="form-control" type="text"   name="country" value="{{Auth::user()->country}}"></dd>
                                            </div>

                                            <div id="phone">
                                                <dt>change phone:</dt>
                                                <dd><input class="form-control" type="text"  name="phone" value="{{Auth::user()->phone}}"></dd>
                                            </div>

                                            <div id="dept">
                                                <dt>change department:</dt>
                                                <dd><input class="form-control" type="text"  name="dept" value="{{Auth::user()->department}}"></dd>
                                            </div>

                                            <div id="website">
                                                <dt>change website:</dt>
                                                <dd><input class="form-control" type="text"  name="website" value="{{Auth::user()->website}}"></dd>
                                            </div>

                                            <div id="aboutMe">
                                                <dt>About Me:</dt>
                                                <dd><input class="form-control" type="text"  name="aboutMe" value="{{Auth::user()->website}}"></dd>
                                            </div>

                                            <div id="level">
                                                <dt>change level:</dt>
                                                <dd><select name="level" class='form-control'>
                                                        @if($user->level == 1)
                                                            <option selected>1</option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                        @elseif($user->level == 2)
                                                            <option >1</option>
                                                            <option selected>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                        @elseif($user->level == 3)
                                                            <option >1</option>
                                                            <option>2</option>
                                                            <option selected>3</option>
                                                            <option>4</option>
                                                        @elseif($user->level == 4)
                                                            <option >1</option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option selected>4</option>
                                                        @endif
                                                    </select></dd>
                                            </div>

                                            <dd> 
                                    </dl>

                                </div>
                                <div class="box-footer">
                                    <input class="btn btn-{{ProfileController::get_class()}} pull-right " type="submit" value="Edit Your Profile"></dd>
                                            {{ Form::token() }}
                                        
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <div class="panel box box-{{ProfileController::get_class()}}">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                                        Change Password
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse4" class="panel-collapse collapse in">

                                <div class="box-body off_white">
                                    <dl class="dl-horizontal">
                                    <form action="{{  URL::route('account-change-password-post') }}" method="post" id="changePassForm">
                                        <div id ="oldPassword">
                                            <dt>Old Password </dt>
                                            <dd><input type="password" class="form-control" name="old_password" id="old_password"></dd>
                                            @if($errors->has('old_password'))
                                                {{ $errors->first('old_password') }}
                                            @endif
                                        </div>

                                        <div id="newPassword">
                                            <dt>New Password</dt>
                                            <dd><input type="password" class="form-control" name="password" id="password"></dd>
                                            @if($errors->has('password'))
                                                {{ $errors->first('password') }}
                                            @endif
                                        </div>

                                        <div id="NewPasswordAgain">
                                            <dt>New Password again</dt>
                                            <dd><input type="password" class="form-control" name="password_again" id="password_again"></dd>
                                            @if($errors->has('password_again'))
                                                {{ $errors->first('password_again') }}
                                            @endif
                                        </div>
                                        </dl>
                                </div>
                                <div class="box-footer">
                                    <input  class="btn btn-{{ProfileController::get_class()}} pull-right" type="submit" value="change Password">
                                        {{ Form::token() }}
                                    </form>
                                </div>
                                    {{ HTML::script('assests//account//changePassForm.js') }}
                                </div>
                            </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <div class="panel box box-{{ProfileController::get_class()}}">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#changeEmail">
                                        Change E-mail
                                    </a>
                                </h4>
                            </div>
                            <div id="changeEmail" class="panel-collapse collapse in">
                                <div class="box-body off_white">
                                    <dl class="dl-horizontal">
                                        <form action="{{  URL::route('account-change-email-post') }}" method="post" id="changeEmailForm">

                                            <div id ="oldEmail">
                                                <dt>Old E-mail </dt>
                                                <dd><input type="email" class="form-control" name="old_Email" id="old_Email"></dd>
                                                @if($errors->has('old_Email'))
                                                    {{ $errors->first('old_Email') }}
                                                @endif
                                            </div>

                                            <div id="newEmail">
                                                <dt>New E-mail</dt>
                                                <dd><input type="email" class="form-control" name="Email" id="Email"></dd>
                                                @if($errors->has('Email'))
                                                    {{ $errors->first('Email') }}
                                                @endif
                                            </div>

                                            <div id="NewEmailAgain">
                                                <dt>New E-mail again</dt>
                                                <dd><input type="email" class="form-control" name="Email_again" id="Email_again"></dd>
                                                @if($errors->has('Email_again'))
                                                    {{ $errors->first('Email_again') }}
                                                @endif
                                            </div>

                                    </dl>
                                </div>
                                <div class="box-footer">
                                    <input  class="btn btn-{{ProfileController::get_class()}} pull-right" type="submit" value="change Email">
                                            {{ Form::token() }}
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

    @else

<div class="box box-solid">
        <div id='profile_banner' style=''>
        <div class="row" >
            <div class="col-md-5">
                <div>
                    <center>
                            <img onclick='view(this)'  src="../../images/{{$user->profile_picture}}" class='img-circle'  style='margin-top:5px;height:120px;width:120px;border:3px solid #fff;cursor:pointer;'>
                            <h2>{{$user->first_name}} {{$user->last_name}}</h2>
                            <h4>{{$user->email}}</h4>
                    </center>
                </div>
            </div>
            <div class="col-md-5">
                <center>
                    <h4>{{ $user->aboutMe }}</h4>
                    <h4> WebSite : {{$user->website}} </h4>
                    <h4> {{$user->department}}</h4>
                    <h4> Live In : {{$user->city}} - {{$user->country}}</h4>

                </center>
            
            </div>
        </div>
        </div>
        
        <div class="row" >
            
            <div class="col-md-7" style='margin:0px;'>
                <div class="box-body">
                    <div class="col-md-10">
            <div class="box-body">
               
            </div>
        </div>
        <div id = "chitchat"></div>
                </div>
            </div>
            <div class="col-md-5" style='margin:0px;'>


                <div class="box-body">
                    <div class='box'>
                        <div class="box-header with-border">
                            <h4 class="box-title">User Info</h4>
                        </div>
                        <div class="box-body off_white">
                              <div>
                                 <script type="text/javascript">
                    function friendStatus (ele,status) {
                        var url = window.location.href ;
                        var split = url.split("/");

                        if(status == 1){
                        $.get($(ele).attr('data-href'),function( data ) {
                         
                        $(ele).attr("data-href","/cancelRequest/"+data.id);
                        $(ele).attr("id","sendRequest");
                        $(ele).attr("class","sendRequest");
                        $(ele).children().val("Request send!");
                        $(ele).attr("onclick","friendStatus(this,2)");
                        
                        $.notify(data.state, "success");
                        },'json');
                       
                        }
                        else if (status == 2){
                            $.get($(ele).attr('data-href'),function( data ) {
                        
                        $.notify(data.state, "success");
                        },'json');
                         
                        $(ele).attr("data-href","/addFriends/{{$user->id}}");
                        $(ele).attr("id","addFriends");
                        $(ele).attr("class","addFriends");
                        $(ele).children().val("Add friend!");
                        $(ele).attr("onclick","friendStatus(this,1)");
                        $(ele).parent().children(".confirmRequest").remove();
                        
                        }
                        else if (status == 3){
                            $.get($(ele).attr('data-href'),function( data ) {
                        
                        $.notify(data.state, "success");
                        },'json');
                         
                        $(ele).attr("data-href","/addFriends/{{$user->id}}");
                        $(ele).attr("id","addFriends");
                        $(ele).attr("class","addFriends");
                        $(ele).children().val("Add friend!");
                        $(ele).attr("onclick","friendStatus(this,1)");
                        
                        }



                    }

                </script>
                
            @if($checkFriends == NULL)
                <a class = "addFriends" id ="" onclick="friendStatus(this,1);" data-href="/addFriends/{{$user->id}}"><input class ="btn btn-block btn-{{ProfileController::get_class()}} btn-sm" type="button" id="addfriendbtn" value="Add Friend"> </a>  
            @else

                @if($checkFriends->active == 0 && $checkFriends->user1_id == Auth::user()->id)
                    <a class = "sendRequest" onclick="friendStatus(this,2);" data-href="/cancelRequest/{{$checkFriends->id}}"><input class ="btn btn-block btn-{{ProfileController::get_class()}} btn-sm" value="Request send!" type="button"></a>
                @elseif($checkFriends->active == 0 && $checkFriends->user1_id != Auth::user()->id)
                 <a class = "sendRequest" onclick="friendStatus(this,2);" data-href="/cancelRequest/{{$checkFriends->id}}"><input class ="btn btn-block btn-{{ProfileController::get_class()}} btn-sm" value="Cancel Request!" type="button"></a>
                   <br/>
                  <a class = "confirmRequest" onclick="confirmFrind(this);" data-href="/confirmRequest/{{$checkFriends->id}}"><input class ="btn btn-block btn-{{ProfileController::get_class()}} btn-sm" value="Confirm Request!" type="button"></a>
                  <script type="text/javascript">

                    function confirmFrind (ele) {
                    $.get($(ele).attr('data-href'),function( data ) {
                        
                        $.notify("dd", "success");
                        },'json');
                        
                        $(ele).attr("data-href","/cancelRequest/{{$checkFriends->id}}");
                        $(ele).attr("id","cancelRequest");
                        $(ele).attr("class","cancelRequest");
                        $(ele).children().val("friends");
                        $(ele).attr("onclick","friendStatus(this,3)");
                        $(ele).parent().children(".sendRequest").remove();
                    }
                  </script>
                @else
                    <a  class = "noFriends" onclick="friendStatus(this,3);" data-href="/cancelRequest/{{$checkFriends->id}}"><input class ="btn btn-block btn-{{ProfileController::get_class()}} btn-sm" value="friends!" type="button"></a>
                @endif
            @endif
                              </div>  
                          <div class="btn-group-vertical" role="group" aria-label="...">
                            
                         </div>          
                        </div>
                    </div>
                </div> 


                <div class="box-body">
                    <div class='box'>
                        <div class="box-header with-border">
                            <h4 class="box-title">Basic Information</h4>
                        </div>
                        <div class="box-body off_white">
                                <ul class='btn-group-vertical'>
                                    <li>Name :{{$user->first_name}} {{$user->middle_name}} {{$user->last_name}}</li>
                                    <li>Birth date : {{$user->birth_date}}</li>
                                    <li>phone : {{$user->phone}}</li>
                                    <li>level : {{$user->level}}</li>
                                    <li>address : {{$user->street}}-{{$user->city}}-{{$user->country}}</li>
                                </ul>
                          <div class="btn-group-vertical" role="group" aria-label="...">
                            
                         </div>          
                        </div>
                    </div>
                </div> 
                <div class="box-body">
                    <div class='box'>
                        <div class="box-header with-border">
                            <h4 class="box-title">Courses</h4>
                        </div>
                        <div class="box-body off_white">
                                <ul class='menu'>
                                    @foreach($groups as $group)
                                        <li>{{$group->name}}</li>
                                        @endforeach

                                </ul>
                        </div>
                    </div>
                </div> 
                <div class="box-body">
                    <div class='box'>
                        <div class="box-header with-border">
                            <h4 class="box-title">Friends</h4>
                        </div>
                        <div class="box-body off_white">
                                <table class='menu' cell-spacing='1'>

                                            <tr>
                                                <td><img src="#" class='pp'></td>
                                                <td><h4>Ahmed Adel</h4></td>
                                            </tr>


                                </table>
                        </div>
                    </div>
                </div> 

            </div>
        </div>
    </div>

        

        @endif


<script>
function Like(announcement_id,user_id){
    $.post('announcements/like-announcement',{'announcement_id':announcement_id,'user_id':user_id},function(data){
        console.log(data.result);
        if (data.result == true) {
        $.notify("Winked", "success");
        }
    });
}
function Delete(elem){
    var index=$(elem).attr('id');
    url='/Announcement/delete-announcement/'+index;
    $.get(url,{},function(d_data){});//end of delete get request
    $(elem).closest('.box-body').remove();
    $.notify("Deleted Successfully", "success");
}
function Edit(elem){
    var index=$(elem).attr('id');
    url='/Announcement/edit-announcement/'+index;
    var edit_form="";
    var self = elem;
    $(elem).closest('.box-group').parent().addClass('edited_div');
    $(elem).closest('.box-group').remove();
    $.get(url,{},function(edit_data){
        console.log(edit_data.announcement)
        edit_form='<div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" href="#EditAnnouncement'+edit_data.announcement[0].id+'" class="" aria-expanded="true">Edit Announcement</a></h4></div><div id="EditAnnouncement'+edit_data.announcement[0].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+edit_data.current_user.profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+edit_data.current_user.user_name+'</p></div></div><form id="edit_form" action="#"><dt>Title: </dt><dd><input type="text" id="form_title" value="'+edit_data.announcement[0].title+'" class="form-control"></dd><dt>Announcement: </dt><dd><textarea id="form_content" class="form-control">'+edit_data.announcement[0].content+'</textarea></dd><br><input type="submit" value="done" id="edit_btn" class="btn btn-{{ProfileController::get_class()}} btn-block btn-lg"></form></div></div></div></div></div>';
        $('.edited_div').prepend(edit_form);
        edit_form ="";
        $("#edit_btn").click(function(e) {
            e.preventDefault();
            var content=$('#form_content').val();
            var title=$('#form_title').val();
            url='/Announcement/edite-announcement/'+index;
            var edit_data="";
            $(this).closest('.box-group').remove();
            $.post(url,{content:content,title:title},function(done_data){
                console.log(done_data)
                edit_data='<div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" href="#Announcement'+done_data.id+'" class="" aria-expanded="true">'+title+'</a></h4><button title="Delete" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+done_data.id+'" onclick="Delete(this)">X</button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+done_data.id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button><button title="Edit" class="pull-right btn btn-xs btn-{{ProfileController::get_class()}}" style="margin-right:3px;margin-top:3px;" id="'+done_data.id+'" onclick="Edit(this)"><i class="fa fa-pencil-square-o fa-2"></i></button></div><div id="Announcement'+done_data.id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+done_data.edit_user.profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+done_data.edit_user.user_name+'</p><p style="color:#CCC;font-size:12px">few second ago</p></div></div><div id="announcement_content">'+content+'</div></div></div></div></div></div>';
                $('.edited_div').append(edit_data);
                edit_data ="";
                $('.edited_div').removeClass('edited_div');
                $.notify("Edited Successfully", "success");
            })//end of post edit
        });//end done btn click
    });//end of edit get request
}
function More(elem,last_announcement_id){
    last_announcement_id--;
    //$(elem).parent().remove();
    $.get('/Announcement/show-announcement',{'announcement_id':last_announcement_id},function(data){
        if(data.announcement.length > 0){
            for(i=0;i<data.announcement.length;i++){
                if(data.at[i]==0){
                    var date=data.date[i];
                }else{
                    var date=data.date[i]+" at " +data.at[i]
                }
                text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" href="#Announcement'+data.announcement[i].id+'" class="" aria-expanded="true" id="titlelink">'+data.announcement[i].title+'</a></h4><button title="Delete" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].id+'" onclick="Delete(this)">X</button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button><button title="Edit" class="pull-right btn btn-xs btn-{{ProfileController::get_class()}}" style="margin-right:3px;margin-top:3px;" id="'+data.announcement[i].id+'" onclick="Edit(this)"><i class="fa fa-pencil-square-o fa-2"></i></button></div><div id="Announcement'+data.announcement[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.user.profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.user.user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                $(elem).closest("#announcement_container").append(text);
                last_announcement_id = data.announcement[i].id;
            }//end of for loop
            text = '<div class="box-footer text-center"><a id="get_more_announcement" class="uppercase" onclick="More(this,'+last_announcement_id+')">view more</a></div>';
            $(elem).closest("#announcement_container").append(text);
            text="";
            $(elem).parent().remove();
        }//end of if length > 0
        else{
            text = '<div class="box-footer text-center">No More Announcement</div>';
            $(elem).closest("#announcement_container").append(text);
            text="";
            $(elem).parent().remove();
        }
    });//end of get announcement content
}

function MoreLevel(elem,last_announcement_id){
    last_announcement_id--;
    $.get('/Announcement/show-level-announcement',{last_announcement_id : last_announcement_id},function(data){
       if(data.announcement.length > 0){
            for(i=0;i<data.announcement.length;i++){
                if(data.at[i]==0){
                    var date=data.date[i];
                }else{
                    var date=data.date[i]+" at " +data.at[i]
                }
                if (data.announcement[i].user_code == {{Auth::user()->user_code}}) {
                    text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" href="#Announcement'+data.announcement[i].announcement_id+'" class="" aria-expanded="true" id="titlelink">'+data.announcement[i].title+'</a></h4><button title="Delete" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'" onclick="Delete(this)">X</button><button title="Edit" class="pull-right btn btn-xs btn-{{ProfileController::get_class()}}" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'" onclick="Edit(this)"><i class="fa fa-pencil-square-o fa-2"></i></button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].announcement_id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcement[i].announcement_id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcement[i].profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.announcement[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                        $("#announcement_level").prepend(text);
                }
                else{
                    text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" href="#Announcement'+data.announcement[i].announcement_id+'" class="" aria-expanded="true" id="titlelink">'+data.announcement[i].title+'</a></h4><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].announcement_id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcement[i].announcement_id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcement[i].profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.announcement[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                        $("#announcement_level").prepend(text);
                }//end else
                    
            }//end for loop
            text = '<div class="box-footer text-center"><a id="get_more_announcement_level" class="uppercase" onclick="MoreLevel(this,'+last_announcement_id+')">view more</a></div>';
            $("#announcement_level").append(text);
            text="";
            $(elem).parent().remove();
        }//eng if
        else{
            text = '<div class="box-footer text-center">No More Announcement</div>';
            $(elem).closest("#announcement_level").append(text);
            text="";
            $(elem).parent().remove();
        }
    }); // end level get announcement
}

$(document).ready(function(){

    
    var last_announcement_id = 0;
    var last_announcement_level_id = 0;
    var text="";
    $.get('announcements/max-announcement-id',{},function(m_data){
        last_announcement_id = m_data.id;
        last_announcement_level_id = m_data.id;
        console.log(last_announcement_id);
        $.get('/Announcement/show-announcement',{'announcement_id':last_announcement_id},function(data){
            console.log(data.announcement)
            for(i=0;i<data.announcement.length;i++){
                if(data.at[i]==0){
                  var date=data.date[i];
                }else{
                  var date=data.date[i]+" at " +data.at[i]
                }
                text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" href="#Announcement'+data.announcement[i].id+'" class="" aria-expanded="true" id="titlelink">'+data.announcement[i].title+'</a></h4><button title="Delete" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].id+'" onclick="Delete(this)">X</button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button><button title="Edit" class="pull-right btn btn-xs btn-{{ProfileController::get_class()}}" style="margin-right:3px;margin-top:3px;" id="'+data.announcement[i].id+'" onclick="Edit(this)"><i class="fa fa-pencil-square-o fa-2"></i></button></div><div id="Announcement'+data.announcement[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.user.profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.user.user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                $("#announcement_container").append(text);
                last_announcement_id = data.announcement[i].id;
            }
            text = '<div class="box-footer text-center"><a id="get_more_announcement" class="uppercase" onclick="More(this,'+last_announcement_id+')">view more</a></div>';
            $("#announcement_container").append(text);
            text="";
        });//end of get announcement content
    });

    $("#form-add-announcement").submit(function(e){
        e.preventDefault();
        var content=$('#addannounce').val();
        var title=$('#title').val();
        var groupselection="ChooseGroup";
        var levelselection=$('#levelselection').val();
        $.post('/Announcement/add-announcement',{content:content,title:title,groupselection:groupselection,levelselection:levelselection},function(r_data){
            var add_text="";
            add_text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" href="#Announcement'+r_data.id+'" class="" aria-expanded="true" id="titlelink">'+title+'</a></h4><button title="Delete" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+r_data.id+'" onclick="Delete(this)">X</button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+r_data.id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button><button title="Edit" class="pull-right btn btn-xs btn-{{ProfileController::get_class()}}" style="margin-right:3px;margin-top:3px;" id="'+r_data.id+'" onclick="Edit(this)"><i class="fa fa-pencil-square-o fa-2"></i></button></div><div id="Announcement'+r_data.id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+r_data.profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+r_data.user+'</p><p style="color:#CCC;font-size:12px">few second ago</p></div></div><div id="announcement_content">'+content+'</div></div></div></div></div></div></div>';
            $("#announcement_container").prepend(add_text);
            add_text="";
            document.getElementById("form-add-announcement").reset();
            $.notify("Success in Add Announcement", "success");
        });//end of post announcement
    });//end of form submite

    $("#announcement_link").click(function(e) {
        e.preventDefault();
        $("#announcements").show();
        $("#announcementlevel").hide();
    });     
        
    $("#level_link").click(function(e) {
        e.preventDefault();
        $("#announcement_level").empty();
        $("#announcements").hide();
        $("#announcementlevel").show();
        $("#announcementlevel").addClass('active');
        console.log(last_announcement_level_id);
        $.get('/Announcement/show-level-announcement',{last_announcement_id : last_announcement_level_id},function(data){
           // console.log(data.level)
            //console.log(data.user)
            var text="<div id='level_name'>Level "+data.level+"</div>"
            var i;
            for(i=0;i<data.announcement.length;i++){
                if(data.at[i]==0){
                  var date=data.date[i];
                }else{
                  var date=data.date[i]+" at " +data.at[i]
                }
                if (data.announcement[i].user_code == {{Auth::user()->user_code}}) {
                    text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" href="#Announcement'+data.announcement[i].announcement_id+'" class="" aria-expanded="true" id="titlelink">'+data.announcement[i].title+'</a></h4><button title="Delete" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'" onclick="Delete(this)">X</button><button title="Edit" class="pull-right btn btn-xs btn-{{ProfileController::get_class()}}" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'" onclick="Edit(this)"><i class="fa fa-pencil-square-o fa-2"></i></button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].announcement_id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcement[i].announcement_id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcement[i].profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.announcement[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                    $("#announcement_level").append(text);
                    last_announcement_level_id = data.announcement[i].announcement_id;
                }
                else{
                    text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" href="#Announcement'+data.announcement[i].announcement_id+'" class="" aria-expanded="true" id="titlelink">'+data.announcement[i].title+'</a></h4><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].announcement_id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcement[i].announcement_id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcement[i].profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.announcement[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                    $("#announcement_level").append(text);
                    last_announcement_level_id = data.announcement[i].announcement_id;
                }//end else
                
            }//end for loop
            console.log(last_announcement_level_id);
            text = '<div class="box-footer text-center"><a id="get_more_announcement_level" class="uppercase" onclick="MoreLevel(this,'+last_announcement_level_id+')">view more</a></div>';
            $("#announcement_level").append(text);
            text="";
        }); // end level get announcement
    });//end show level click
    






    $.get('announcements/max-announcement-id',{},function(m_data){
        last_announcement_id = m_data.id;
        $.get('/chichat/GetAllUser',{last_announcement_id : last_announcement_id,user_id:{{$user->id}}},function(data){
            console.log(data.announcement);
            console.log(data.at)
            console.log(data.date)
            for(i=0;i<data.announcement.length;i++){
                if(data.at[i]==0){
                  var date=data.date[i];
                }else{
                  var date=data.date[i]+" at " +data.at[i]
                }
               
               
                    text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><button title="Comment" class="comment pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'"><i class="fa fa-comments-o"></i></button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].announcement_id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcement[i].announcement_id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcement[i].profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.announcement[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                    $("#chitchat").append(text);
                    last_announcement_id = data.announcement[i].announcement_id;
            
            }//end for loop
        }); // end level get announcement
    });




});//end document ready
</script>


    <script>
        jQuery( document ).ready( function( $ ) {

            

            /*$('a.noFriends').click(function(event){
                $.get($(this).attr('href'),function( data ) {
                           // alert('success in delete friend !');
                            $.notify(data.state, "success");
                        },
                        'json');
                return false;
            });*/

            /*$('a.sendRequest').click(function(event){
                $.get($(this).attr('href'),function( data ) {
                            //alert('success in cancel request !');
                            $.notify(data.state, "success");
                        },
                        'json');
                return false;
            });*/

            $('a.changeColor').click(function(event){
                $.get($(this).attr('href'),function( data ) {
                            if(data.state == 'success') {
                                $.notify(data.state, "success");
                                location.reload();s
                            }
                            else
                                $.notify(data.state, "warn");
                        },
                        'json');
                return false;
            });


            $('#editProfileForm').on( 'submit', function() {
                $.post(
                        $( this ).prop( 'action' ),
                        $('form#editProfileForm').serialize(),
                        function( data ) {
                            //alert('success in edit'
                            $.notify(data.state, "success");
                           // document.getElementById("quiz").reset();
                        },
                        'json'
                );
                return false;
            } );

            $('#changePassForm').on( 'submit', function() {
                $.post(
                        $( this ).prop( 'action' ),
                        $('form#changePassForm').serialize(),
                        function( data ) {
                            //alert(data.state);
                            if(data.state == 'success')
                            $.notify('success in change password', "success");
                            else
                                $.notify('Your old password is incorrect', "warm");
                            document.getElementById("changePassForm").reset();
                        },
                        'json'
                );
                return false;
            } );

            $('#changeEmailForm').on( 'submit', function() {
                $.post(
                        $( this ).prop( 'action' ),
                        $('form#changeEmailForm').serialize(),
                        function( data ) {
                            //alert(data.state);
                            if(data.state == 'error') {
                                $.notify('error in change e-mail', "warm");
                            }
                            else if(data.state == 'oldValid') {
                                $.notify('Your old e-mail is incorrect', "warm");
                            }
                            else if(data.state == 'success'){
                                $.notify('change email successed', "success");
                                document.getElementById("changeEmailForm").reset();}
                        },
                        'json'
                );
                return false;
            } );

/*
            $("#uploadimage").on('submit',(function(e) {
                e.preventDefault();
                $("#message").empty();
                $('#loading').show();
                $.post({
                    url: "image-upload", // Url to which the request is send
                    type: "POST",             // Type of request to be send, called as method
                    data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData:false,        // To send DOMDocument or non processed data file it is set to false
                    success: function(data)   // A function to be called if request succeeds
                    {
                        $('#loading').hide();
                        $("#message").html(data);
                    }
                });
            }));
            */
/*
            $('#uploadimage').on( 'submit', function() {
                e.preventDefault();
                $("#message").empty();
                $.post(
                        $( this ).prop( 'action' ),
                        $('form#uploadimage').serialize(),
                        function( data ) {
                            //alert(data.state);
                            if(data.state == 'success')
                            $.notify('success', "success");
                            else
                                $.notify('error', "warm");

                            //$('#loading').hide();
                            //$("#message").html(data);
                            // document.getElementById("quiz").reset();
                        },
                        'json'
                );
                return false;
            });

*/
            $(function() {
                $("#file").change(function() {
                    $("#message").empty(); // To remove the previous error message
                    var file = this.files[0];
                    var imagefile = file.type;
                    var match= ["image/jpeg","image/png","image/jpg"];
                    if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
                    {
                        //$('#previewing').attr('src','noimage.png');
                        $("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                        return false;
                    }
                    else
                    {
                        var reader = new FileReader();
                        reader.onload = imageIsLoaded;
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });

            function imageIsLoaded(e) {
                $("#file").css("color","green");
                $('#image_preview').css("display", "block");
                $('#previewing').attr('src', e.target.result);
                $('#previewing').attr('width', '250px');
                $('#previewing').attr('height', '230px');
            };
            var myclass="bg-"+colorName;

            $("#profile_banner").addClass(myclass);
            });

    </script>


@endsection
@stop