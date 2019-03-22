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
    </script>

    {{ HTML::style('css/TSstyle.css') }}

    <style type="text/css">
        .qContent{white-space: pre}
        .post_cont a{color: #fff;}
        .post_cont h3{color: #fff;}
        .post_cont{border-radius: 4px; border-color: #337ab7;}
    </style>

@stop

@section('content')

    @if($check == true)
        <div class='col-md-8'>
            <div style='max-width:900px;margin:0px auto;'>
                <div>
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul id='menu' class="nav nav-tabs">
                            <li><a href="#announcements" data-toggle="tab">Announcements</a></li>
                            <li><a href="#" data-toggle="tab">Votes</a></li>
                            <li><a href="#" data-toggle="tab">Complaints</a></li>
                            <li><a href="#" data-toggle="tab">Calender</a></li>
                            <li><a href="#details" data-toggle="tab">Memebers</a></li>
                        </ul>

                        <div id='tape_contents' class="tab-content">
                            <div  class="tab-pane" id='announcements'>

                                <div id="form_container">
                                    <div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#addAnnouncement" class="" aria-expanded="true">Add Announcement#</a></h4></div><div id="addAnnouncement" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><form id="form-add-announcement" action="#"><div id="div_title"><dt>Title:</dt><dd><input type="text" name="title" id="title" class="form-control" placeholder="Add Title To Announcement" title="Announcement Title" required=""></dd></div><div id="div_content"><dt>Announce It:</dt><dd><textarea aria-expanded="true" id="addannounce" class="form-control" name="addannounce" placeholder="What you have to say?" title="Official Announcement" required=""></textarea></dd></div><br><div><input type="submit" value="Add Announcement" id="submbtn" class="btn btn-{{ProfileController::get_class()}} btn-block btn-lg"></div></form></div></div></div> </div></div>
                                </div>
                                <div id="announcement_container"></div>
                            </div>

                            <div  class="tab-pane " id='group_content'></div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane row" style='margin:0px;padding:0px;' id="details">
                                <div class='col-md-12'>
                                    <div class="box box-{{ProfileController::get_class()}} box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Group Admins</h3>

                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                            <ul class="users-list clearfix">
                                                {{--*/ $count = 0 /*--}}

                                                @foreach ($admins as $admin)
                                                    @if(Auth::user()->user_name == $admin->user_name)
                                                        {{--*/ $count = 1 /*--}}
                                                    @endif
                                                    <li>
                                                        <a href="/user/{{$admin->user_code}}"> <img style='width:70px;height:70px;' src="../../images/{{$admin->profile_picture}}" alt="User Image"/>
                                                        {{$admin->user_name}}</a>
                                                        <span class="users-list-date">Today</span>
                                                    </li>


                                                @endforeach
                                            </ul><!-- /.users-list -->
                                        </div><!-- /.box-body -->

                                    </div><!-- /.box -->



                                    @if($count == 1)
                                        <div class="box box-{{ProfileController::get_class()}} box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">New Members</h3>

                                            </div><!-- /.box-header -->
                                            <div class="box-body">
                                                <div class='callout bg-primary '>Group Request</div>
                                                {{--*/ $count = 0 /*--}}
                                                @foreach ($groupRequest as $mem)
                                                    {{$mem->user_name}} <a class = 'do-something' href="/group/request/{{$groupInf->id}}/{{$mem->id}}"> ACCEPT </a>
                                                @endforeach


                                                <div class='box box-{{ProfileController::get_class()}}'>
                                                    <div class='box-header with-border'>
                                                        <h3 class='box-title'>Add New Member</h3>
                                                    </div>
                                                    <div class='box-body'>
                                                        <form action="/group/addMemebr/{{$groupInf->id}}" method="post" id="addMember" enctype="multipart/form-data">
                                                            <input class='form-control col-xs-5' type="text" name="code" id="code"
                                                                   placeholder="User COde"
                                                                    >

                                                            <div class="input-group">
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" name="radio" value="1">
                                                                        Admin
                                                                    </label>
                                                                </div>
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" name="radio" value="0">
                                                                        Student
                                                                    </label>
                                                                </div>
                                                            </div><!-- /input-group -->
                                                            <div class="box-footer">
                                                                <input class='btn btn-primary' type="submit" value="Add Member">
                                                            </div>

                                                            {{ Form::token() }}
                                                        </form>
                                                    </div>
                                                </div>
                                            </div><!-- /.box-body -->

                                        <div class='box box-{{ProfileController::get_class()}}'>
                                            <div class='box-header with-border'>
                                                <h3 class='box-title'>Remove Member</h3>
                                            </div>

                                            <div class='box-body'>
                                                <form action="/group/removeMemebr/{{$groupInf->id}}" method="post" id="removeMember" enctype="multipart/form-data">
                                                    <input class='form-control col-xs-5' type="text" name="code" id="code"
                                                           placeholder="User COde" required
                                                            >
                                                    <div class="box-footer">
                                                        <input class='btn btn-{{$class}} pull-right' type="submit" value="Remove Member">
                                                    </div>
                                                    {{ Form::token() }}
                                                </form>
                                            </div>
                                        </div>
                                @endif

                                    <div class="box box-{{ProfileController::get_class()}} box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Group Members</h3>

                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                            <ul class="users-list clearfix">
                                                @foreach ($members as $member)
                                                    <li>
                                                        <a href="/user/{{$member->user_code}}"><img style='width:90px;height:90px;' src="../../images/{{$member->profile_picture}}" alt="User Image"/>
                                                        {{$member->user_name}}</a>
                                                        <span class="users-list-date">Today</span>
                                                    </li>


                                                @endforeach
                                            </ul><!-- /.users-list -->
                                        </div><!-- /.box-body -->

                                        <div class="box-footer text-center">
                                            <a href="javascript::" class="uppercase">View All Users</a>
                                        </div><!-- /.box-footer -->
                                    </div><!-- /.box -->


                                </div>


                            </div><!-- /.tab-pane -->

                        </div><!-- /.tab-content -->
                    </div><!-- nav-tabs-custom -->
                </div><!-- /.col -->
            </div>
        </div>

    @else

        <div class='col-md-8'>
            <div style='max-width:900px;margin:0px auto;'>
                <div>
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul id='menu' class="nav nav-tabs">
                            <li><a href="#" data-toggle="tab">Complaints</a></li>
                            <li><a href="#" data-toggle="tab">Calender</a></li>
                            @if(Auth::user()->profession == 'employee')
                            <li><a href="#details" data-toggle="tab">Memebers</a></li>
                                @endif
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
    @endif
    <div class='col-md-4'>

        <div class="box box-{{ProfileController::get_class()}} box-solid">

            <div class="box-header with-border">
                <h3 class="box-title">{{$groupInf->name}}</h3>
            </div><!-- /.box-header -->
            <div class="box-body" style='background:#efefef;'>

                <div class="box box-{{ProfileController::get_class()}}">
                    <div class="box-header with-border">
                        <h3 class="box-title">Group Description</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class='qContent panel'>{{$groupInf->description}}</div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

        </div>


    </div>
<script type="text/javascript">
    $(document).ready(function() {
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

        $.get('announcements/max-announcement-id',{},function(data){
            last_announcement_id=data.id;
            $.get('announcements/show-announcement-group',{group_id:{{$groupInf->id}},announcement_id:last_announcement_id},function(data){
                console.log(data.announcements)
                console.log(data.at)
                for(i=0;i<data.announcements.length;i++){
                    if(data.at[i]==0){
                        var date=data.date[i];
                    }else{
                        var date=data.date[i]+" at " +data.at[i]
                    }
                    var text='<ul id="announcement'+data.announcements[i].id+'" class="timeline"><li class="time-label"><span class="bg-'+color+'"><i class="fa fa-user"></i> '+data.announcements[i].user_name+'</span></li><li><a href="/user/'+data.announcements[i].user_code+'"><img class="pp" style="border-radius:50%;" src="http://localhost:8000/images/'+data.announcements[i].profile_picture+'"></a><div class="timeline-item" style="margin-top:-60px;"><div class="box-body " style="background:transparent;"><div class="panel box box-{{ProfileController::get_class()}} box-solid"><div class="box-header with-border"><div class="arrow border-right-{{ProfileController::get_class()}} pull-left"></div><h3 class="box-title">'+data.announcements[i].title+'</h3></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body" style="overflow:hidden;height:auto"><blockquote><p></p><div class="qContent">'+data.announcements[i].content+'</div><p></p><small>'+date+'</small><span class="bg-'+color+'"><button class="comment btn btn-{{ProfileController::get_class()}} btn-flat" style="font-size:15px;border-radius:5px;" title="'+data.announcements[i].id+'"><i class="fa fa-comments-o"></i> Comment</button></span></blockquote><div class="comment_container'+data.announcements[i].id+'"></div></div></div></div></div></div></li></ul>';
                    $("#announcement_container").append(text);
                    last_announcement_id=data.announcements[i].id;
                    var announcements = data.announcements
                    console.log('{{Auth::user()->profile_picture}}')
                }// end of for loop
                $(".comment").click(function(e) {
                    e.preventDefault();
                    var id = $(this).attr('title');
                    $(this).prop("disabled",true);
                    var invoke = ".comment_container"+id;
                    console.log(invoke);
                    var text='<div style="margin-bottom:5px;"><div class="col-md-2" style="margin-bottom:5px;"><img class="img-rounded img-responsive" style="width:40px;height:30px" src="http://localhost:8000/images/{{Auth::user()->profile_picture}}" ></div><div class="col-md-8"><form id = "add_comment"><input required="" type="text" name="comment" id="comment" class="form-control" placeholder="Add comment To Announcement" title="Announcement Comment"></div><div class="col-md-2"><button class="comment_submit btn btn-{{ProfileController::get_class()}} btn-flat" style="border-radius:5px;">Comment</button></form></div></div><div style="clear: both;"></div>';
                    $(invoke).append(text);
                    $.get('announcements/show-announcement-comment',{announcement_id:id},function(c_data){
                        console.log(c_data.comments);
                        for(i=0;i<c_data.comments.length;i++){
                            console.log(c_data.comments[i].user_name);
                            var text = '<li><div style=""><a href="/user/'+c_data.comments[i].user_code+'"><img class="pp" style="border-radius:50%;width:50px;" src="http://localhost:8000/images/'+c_data.comments[i].profile_picture+'"></a></div><div class="timeline-item" style="margin-top:-60px;"><div class="box-body " style="background:transparent;width:80%;margin-left:60px;"><div class="panel box box-{{ProfileController::get_class()}} box-solid"><div class="box-header with-border"><div class="arrow border-right-{{ProfileController::get_class()}} pull-left"></div><h3 class="box-title">'+c_data.comments[i].user_name+'</h3></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body" style="overflow:hidden;height:auto"><blockquote><p></p><div class="qContent">'+c_data.comments[i].content+'</div><p></p><small>few sec ago</small></blockquote></div></div></div></div></div></div></li>';             
                            $(invoke).append(text);
                        }//end of for loop

                    });
                    $(".comment_submit").click(function(e) {
                        e.preventDefault();
                        var comment = $('#comment').val();
                        $.post('announcements/add-announcement-comment',{content:comment,announcement_id:id},function(){
                            var text = '<li><div style=""><a href="/user/{{Auth::user()->user_code}}"><img class="pp" style="border-radius:50%;width:50px;" src="http://localhost:8000/images/{{Auth::user()->profile_picture}}"></a></div><div class="timeline-item" style="margin-top:-60px;"><div class="box-body " style="background:transparent;width:80%;margin-left:60px;"><div class="panel box box-{{ProfileController::get_class()}} box-solid"><div class="box-header with-border"><div class="arrow border-right-{{ProfileController::get_class()}} pull-left"></div><h3 class="box-title">{{Auth::user()->user_name}}</h3></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body" style="overflow:hidden;height:auto"><blockquote><p></p><div class="qContent">'+comment+'</div><p></p><small>few sec ago</small></blockquote></div></div></div></div></div></div></li>';                                
                            $(invoke).append(text);
                            document.getElementById("add_comment").reset();
                        }); //end post add comment
                    });// end of submit comment click
                });// end of .comment click
                data = [];
            });// end of show announcement
        });//end of max id

         var j = 0;
        $(window).scroll(function (e) {
            e.preventDefault();
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
                        var text='<ul id="announcement'+data.announcements[i].id+'" class="timeline"><li class="time-label"><span class="bg-'+color+'"><i class="fa fa-user"></i> '+data.announcements[i].user_name+'</span></li><li><a href="/user/'+data.announcements[i].user_code+'"><img class="pp" style="border-radius:50%;" src="http://localhost:8000/images/'+data.announcements[i].profile_picture+'"></a><div class="timeline-item" style="margin-top:-60px;"><div class="box-body " style="background:transparent;"><div class="panel box box-{{ProfileController::get_class()}} box-solid"><div class="box-header with-border"><div class="arrow border-right-{{ProfileController::get_class()}} pull-left"></div><h3 class="box-title">'+data.announcements[i].title+'</h3></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body" style="overflow:hidden;height:auto"><blockquote><p></p><div class="qContent">'+data.announcements[i].content+'</div><p></p><small>'+date+'</small><span class="bg-'+color+'"><button class="comment'+j+' btn btn-{{ProfileController::get_class()}} btn-flat" style="font-size:15px;border-radius:5px;" title="'+data.announcements[i].id+'"><i class="fa fa-comments-o"></i> Comment</button></span></blockquote><div class="comment_container'+data.announcements[i].id+'"></div></div></div></div></div></div></li></ul>'
                        last_announcement_id=data.announcements[i].id;
                        $("#announcement_container").append(text);
                    }// end of for loop
                    var url = ".comment" + j;

                    $(url).click(function(e) {
                        e.preventDefault();
                        var id = $(this).attr('title');
                        $(this).prop("disabled",true);
                        var invoke = ".comment_container"+id;
                        console.log(invoke);
                        var text='<div style="margin-bottom:5px;"><div class="col-md-2" style="margin-bottom:5px;"><img class="img-rounded img-responsive" style="width:40px;height:30px" src="http://localhost:8000/images/{{Auth::user()->profile_picture}}" ></div><div class="col-md-8"><form id = "add_comment"><input required="" type="text" name="comment" id="comment" class="form-control" placeholder="Add comment To Announcement" title="Announcement Comment"></div><div class="col-md-2"><button class="comment_submit btn btn-{{ProfileController::get_class()}} btn-flat" style="border-radius:5px;">Comment</button></form></div></div><div style="clear: both;"></div>';
                        $(invoke).append(text);
                        $(".comment_submit").click(function(e) {
                            e.preventDefault();
                            var comment = $('#comment').val();
                            console.log(comment);
                                   // var invoke = "#announcement"+id
                            $.post('announcements/add-announcement-comment',{content:comment,announcement_id:id},function(data){
                                 var text = '<li><div style=""><a href="/user/{{Auth::user()->user_code}}"><img class="pp" style="border-radius:50%;width:50px;" src="http://localhost:8000/images/{{Auth::user()->profile_picture}}"></a></div><div class="timeline-item" style="margin-top:-60px;"><div class="box-body " style="background:transparent;width:80%;margin-left:60px;"><div class="panel box box-{{ProfileController::get_class()}} box-solid"><div class="box-header with-border"><div class="arrow border-right-{{ProfileController::get_class()}} pull-left"></div><h3 class="box-title">{{Auth::user()->user_name}}</h3></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body" style="overflow:hidden;height:auto"><blockquote><p></p><div class="qContent">'+comment+'</div><p></p><small>few sec ago</small></blockquote></div></div></div></div></div></div></li>';
                                console.log(invoke);                                
                                $(invoke).append(text);
                                document.getElementById("add_comment").reset();
                            });// end of post comment
                           
                        });// end of submit comment click
                    });// end of .comment click
                    j++;
                });//end of show announcement
            }// end of if statmant
        });// end of windows scroll


        
            
        $("#form-add-announcement").submit(function(e){
            e.preventDefault();
            var content=$('#addannounce').val();
            var title=$('#title').val();
            var groupselection={{$groupInf->id}};
            console.log(groupselection);
            console.log(content);   
            console.log(title);
            $.post('announcements/add-announcement-group',{content:content,title:title,groupselection:groupselection,levelselection:'ChooseLevel'},function(data){
                console.log(data.id);
                var text='<ul id="announcement" class="timeline"><li class="time-label"><span class="bg-'+color+'"><i class="fa fa-user"></i> '+data.user+'</span></li><li><a href="/user/'+data.user_code+'"><img class="pp" style="border-radius:50%;" src="http://localhost:8000/images/'+data.profile_picture+'"></a><div class="timeline-item" style="margin-top:-60px;"><div class="box-body " style="background:transparent;"><div class="panel box box-{{ProfileController::get_class()}} box-solid"><div class="box-header with-border"><div class="arrow border-right-{{ProfileController::get_class()}} pull-left"></div><h3 class="box-title">'+title+'</h3></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body"><blockquote><p></p><div class="qContent">'+content+'</div><p></p><small>few seconds ago</small></blockquote></div></div></div></div></div></li></ul>';
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

@stop