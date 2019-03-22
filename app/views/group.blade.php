@extends('layout/mainTheme')

@section('header')
 
<script>

  var group = {}
  Object.defineProperty( group, "id", {
    value: {{$groupInf->id}},
    writable: false,
    enumerable: true,
    configurable: true
  });
  
  
  
  var groupId=group.id;
  var is_member={{$check}};
  
</script>

    {{ HTML::script('module/globalController.js') }}
    {{ HTML::script('module/questionControllers.js') }}
    {{ HTML::script('module/quizeController.js') }}
    {{ HTML::script('module/VoteController.js') }}
    {{ HTML::script('module/materialController.js')}}
    {{ HTML::style('css/TSstyle.css') }}
  
    <style type="text/css">
    
    .post_cont a{color: #fff;}
    .post_cont h3{color: #fff;}
    .post_cont{border-radius: 4px; border-color: #337ab7;}
    .nav-tabs-custom>.nav-tabs>li.active{border-top:3px solid #f4f4f4;
   //   border-top-width:1px; border-bottom: 0px solid;
    }
    #menu li a{font-weight: 600;}
    #picture_maximum{width: 80%;height: 70%;background: #555;border:5px solid #ddd;position: fixed;
      z-index: 500000;display: none;}
    </style>

@stop
 
@section('content')
  {{--*/ $class = ProfileController::get_class(); /*--}}

    @if($check==true)

      
<div class='col-md-8'>
  <div ng-app="myApp" style='max-width:900px;margin:0px auto;'>
      <!-- Custom Tabs -->
      <div class="nav-tabs-custom">
        <ul id='menu' class="nav nav-tabs">
          <li><a href="#announcements" data-toggle="tab">Announcements</a></li>
          <li><a href="#questions" data-toggle="tab">Questions</a></li>
          <li><a href="#assignments" data-toggle="tab">Assignments</a></li>
          <li><a href="#VoteList" data-toggle="tab">Votes</a></li>
          <li><a href="#quizzes" data-toggle="tab">quizzes</a></li>
          <li><a href="#material" data-toggle="tab">Material</a></li>
          <li><a href="#details" data-toggle="tab">Memebers</a></li>
        </ul>
        <div id='tape_contents' class="tab-content">
          <div  class="tab-pane" id='announcements'  >
            <div id="form_container"></div>
            <div id="announcement_container"></div>
          </div>     
          <div  class="tab-pane " id='group_content'  ng-view></div> 
          <div class="tab-pane row" style='margin:0px;padding:0px;' id="details">
            <div class='col-md-12'>
                <div class="box box-{{$class}} box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Group Admins</h3>

                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                        <ul class="users-list clearfix">
                            {{--*/ $count = 0 /*--}}

                            @foreach ($admins as $admin)
                            @if(Auth::user()->user_name == $admin->user_name)
                            {{--*/ $count = 1 /*--}}
                            @endif
                            <li>
                                <img style='width:70px;height:70px;' src="../../images/{{$admin->profile_picture}}" alt="User Image"/>
                                <a class="users-list-name"  href="/user/{{$admin->user_code}}">{{$admin->user_name}}</a>
                                <span class="users-list-date">Today</span>
                            </li>
                            @endforeach
                        </ul><!-- /.users-list -->
                    </div><!-- /.box-body -->

                </div><!-- /.box -->



                @if($count == 1)
                <div class="box box-{{$class}} box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">New Members</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class='callout bg-{{$class}} '>Group Request</div>
                        {{--*/ $count = 0 /*--}}
                        @foreach ($groupRequest as $mem)
                        {{$mem->user_name}} <a class = 'do-something' href="/group/request/{{$groupInf->id}}/{{$mem->id}}"> ACCEPT </a>
                        @endforeach    
                        <div class='box box-{{$class}}'>
                            <div class='box-header with-border'>
                                <h3 class='box-title'>Add New Member</h3>
                            </div>
                            <div class='box-body'>
                                <form action="/group/addMemebr/{{$groupInf->id}}" method="post" id="addMember" enctype="multipart/form-data">
                                    <input class='form-control col-xs-5' type="text" name="code" id="code"placeholder="User COde">
                                    <div class="input-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="radio" value="1">Admin 
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="radio" value="0">Student
                                            </label>
                                        </div>
                                    </div><!-- /input-group -->
                                    <div class="box-footer">
                                        <input class='btn btn-{{$class}} pull-right' type="submit" value="Add Member">
                                    </div>
                                    {{ Form::token() }}
                                </form>
                            </div>
                        </div>


<div class='box box-{{$class}}'>
                          <div class='box-header with-border'>
                              <h3 class='box-title'>Remove Member</h3>
                          </div>
                          <form action="/group/removeMemebr/{{$groupInf->id}}" method="post" id="removeMember" enctype="multipart/form-data">   

                              <div class='box-body'>
                                  <input class='form-control' type="text" name="code" id="code" placeholder="User COde" required>


                              </div>
                              <div class='box-footer'>
                                  <input class='btn btn-{{$class}} pull-right' type="submit" value="Remove Member">

                              </div>
                              {{ Form::token() }}

                          </form>


                      </div>
                        
                    </div><!-- /.box -->
                    

                    


                </div>
                @endif

                <div class="box box-{{$class}} box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Group Members</h3>

                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <ul class="users-list clearfix">
                                @foreach ($members as $member)
                                <li>
                                    <img style='width:70px;height:70px;' src="../../images/{{$member->profile_picture}}" alt="User Image"/>
                                    <a class='users-list-name' href="/user/{{$member->user_code}}"> {{$member->user_name}}</a>
                                    <span class="users-list-date">Today</span>
                                </li>
                                @endforeach
                            </ul><!-- /.users-list -->
                        </div><!-- /.box-body -->

                        <div class="box-footer text-center">
                            <a href="javascript::" class="uppercase">View All Users</a>
                        </div><!-- /.box-footer -->
                    </div><!-- /.box -->

            </div><!-- col 12 -->
          </div><!-- tab pan-->
        </div><!--tab content-->
      </div><!--nav tab custom-->
  </div>

</div><!--col -->
    
 <div class='col-md-4'>
                  <div class="box box-{{$class}} box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">{{$groupInf->name}}</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body" style='background:#efefef;'>
                        <div class="box box-{{$class}}">
                          <div class="box-header with-border">
                            <h3 class="box-title">Course Syllable</h3>
                          </div><!-- /.box-header -->
                          <div class="box-body">
                            <div class='qContent panel'>{{$groupInf->syllable}}</div>
                          </div><!-- /.box-body -->
                        </div><!-- /.box -->
                        <div class="box box-{{$class}}">
                          <div class="box-header with-border">
                            <h3 class="box-title">Grade Police</h3>
                          </div><!-- /.box-header -->
                          <div class="box-body">
                           <div class='qContent panel'>{{$groupInf->grade_police}}</div>
                          </div><!-- /.box-body -->
                        </div><!-- /.box -->
                        <div class="box box-{{$class}}">
                          <div class="box-header with-border">
                            <h3 class="box-title">Course Description</h3>
                          </div><!-- /.box-header -->
                          <div class="box-body">
                            <div class='qContent panel'>{{$groupInf->description}}</div>
                          </div><!-- /.box-body -->
                        </div><!-- /.box -->

                    </div>
                  </div>

                
</div>

@else
        you not member in this group .
        click here to send request to group :
        <form action="/group/request/{{$groupInf->id}}" method="post" id="requestGroup">
            <input type="submit" value="send Request">
            {{ Form::token() }}
        </form>
        
@endif
<script>
	$(document).ready(function() {
    var last_announcement_id=0;
    var color;
    var color_class= '{{ProfileController::get_class()}}' ;
    switch(color_class){

      case 'success':
        color = 'green';
        break;
      case 'primary':
        color = 'blue';
        break;
      case 'danger':
        color = 'red';
        break;
      case 'default':
        color = 'purple';
        break;
      case 'warning':
        color = 'yellow';
        break;
      default :
        color = 'black';
        break;
    }
		var text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#addAnnouncement" class="" aria-expanded="true">Add Announcement#</a></h4></div><div id="addAnnouncement" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><form id="form-add-announcement" action="#"><div id="div_title"><dt>Title:</dt><dd><input type="text" name="title" id="title" class="form-control" placeholder="Add Title To Announcement" title="Announcement Title" required=""></dd></div><div id="div_content"><dt>Announce It:</dt><dd><textarea aria-expanded="true" id="addannounce" class="form-control" name="addannounce" placeholder="What you have to say?" title="Official Announcement" required=""></textarea></dd></div><br><div class="box-footer"><input type="submit" value="Add Announcement" id="submbtn" class="btn btn-{{ProfileController::get_class()}} pull-right"></div></form></div></div></div> </div></div>'
		$("#form_container").append(text);
		$.get('announcements/max-announcement-id',{},function(data){
      last_announcement_id=data.id;
      $.get('announcements/show-announcement-group',{group_id:{{$groupInf->id}},announcement_id:last_announcement_id},function(data){
          console.log(last_announcement_id);
          console.log(data.announcements);
          console.log(data.at);
          for(i=0;i<data.announcements.length;i++){
            if(data.at[i]==0){
              var date=data.date[i];
            }else{
              var date=data.date[i]+" at " +data.at[i]
            }
            var text='<ul id="announcement" class="timeline"><li class="time-label"><span class="bg-'+color+'"><i class="fa fa-user"></i> '+data.announcements[i].user_name+'</span></li><li><a href="/user/'+data.announcements[i].user_code+'"><img class="pp" style="border-radius:50%;" src="'+path_prefix+'images/'+data.announcements[i].profile_picture+'"></a><div class="timeline-item" style="margin-top:-60px;"><div class="box-body " style="background:transparent;"><div class="panel box box-{{ProfileController::get_class()}} box-solid"><div class="box-header with-border"><div class="arrow border-right-{{ProfileController::get_class()}} pull-left"></div><h3 class="box-title">'+data.announcements[i].title+'</h3></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body"><blockquote><p></p><div class="qContent">'+data.announcements[i].content+'</div><p></p><small>'+date+'</small></blockquote></div></div></div></div></div></li></ul>'
            $("#announcement_container").append(text);
            last_announcement_id=data.announcements[i].id;
            console.log(last_announcement_id)
          }
          data = [];
      });
  
    });


 $(window).scroll(function () {
        if ($(document).height() <= $(window).scrollTop() + $(window).height()) {
          
          last_announcement_id--;
          console.log(last_announcement_id)
          $.get('announcements/show-announcement-group',{group_id:{{$groupInf->id}},announcement_id:last_announcement_id},function(data){
              console.log(last_announcement_id);
              console.log(data.announcements);
              console.log(data.at);
              for(i=0;i<data.announcements.length;i++){
                if(data.at[i]==0){
                  var date=data.date[i];
                }else{
                  var date=data.date[i]+" at " +data.at[i]
                }
                var text='<ul id="announcement" class="timeline"><li class="time-label"><span class="bg-'+color+'"><i class="fa fa-user"></i> '+data.announcements[i].user_name+'</span></li><li><a href="/user/'+data.announcements[i].user_code+'"><img class="pp" style="border-radius:50%;" src="'+path_prefix+'images/'+data.announcements[i].profile_picture+'"></a><div class="timeline-item" style="margin-top:-60px;"><div class="box-body " style="background:transparent;"><div class="panel box box-{{ProfileController::get_class()}} box-solid"><div class="box-header with-border"><div class="arrow border-right-{{ProfileController::get_class()}} pull-left"></div><h3 class="box-title">'+data.announcements[i].title+'</h3></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body"><blockquote><p></p><div class="qContent">'+data.announcements[i].content+'</div><p></p><small>'+date+'</small></blockquote></div></div></div></div></div></li></ul>'
                $("#announcement_container").append(text);
                last_announcement_id=data.announcements[i].id;
              }

          });
        }
    });
  
		$("#form-add-announcement").submit(function(e){
			e.preventDefault();
			var content=$('#addannounce').val();
			var title=$('#title').val();
			var groupselection=group.id;
			$.post('announcements/add-announcement-group',{content:content,title:title,groupselection:groupselection,levelselection:'ChooseLevel'},function(data){
				console.log(data.user)
				var text='<ul id="announcement" class="timeline"><li class="time-label"><span class="bg-'+color+'"><i class="fa fa-user"></i> '+data.user+'</span></li><li><a href="/user/'+data.user_code+'"><img class="pp" style="border-radius:50%;" src="'+path_prefix+'images/'+data.profile_picture+'"></a><div class="timeline-item" style="margin-top:-60px;"><div class="box-body " style="background:transparent;"><div class="panel box box-{{ProfileController::get_class()}} box-solid"><div class="box-header with-border"><div class="arrow border-right-{{ProfileController::get_class()}} pull-left"></div><h3 class="box-title">'+title+'</h3></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body"><blockquote><p></p><div class="qContent">'+content+'</div><p></p><small>few seconds ago</small></blockquote></div></div></div></div></div></li></ul>'
				$("#announcement_container").prepend(text);
        document.getElementById("form-add-announcement").reset();
			});//post add announcement
		});//form submit
});//document ready
</script>



<script>
    jQuery( document ).ready( function( $ ) {

        $('#addMember').on( 'submit', function() {
            $.post(
                    $( this ).prop( 'action' ),
                    $('form#addMember').serialize(),
                    function( data ) {
                        alert(data.message);
                        document.getElementById("addMember").reset();
                    },
                    'json'
            );
            return false;
        } );

        $('#removeMember').on( 'submit', function() {
            $.post(
                    $( this ).prop( 'action' ),
                    $('form#removeMember').serialize(),
                    function( data ) {
                        alert(data.message);
                        document.getElementById("removeMember").reset();
                    },
                    'json'
            );
            return false;
        } );

        $('#requestGroup').on( 'submit', function() {
            $.post(
                    $( this ).prop( 'action' ),
                    $('form#requestGroup').serialize(),
                    function( data ) {
                        alert(data.message);
                    },
                    'json'
            );
            return false;
        } );

        $('a.do-something').click(function(event){
            $.get($(this).attr('href'),function( data ) {
                        alert(data.message);
                    },
                    'json');
            return false;
        });

    } );

</script>
@endsection
@stop