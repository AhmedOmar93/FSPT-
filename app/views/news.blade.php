@extends('layout.mainTheme')

@section('content')
<div class='col-md-8'>
	<div id="form_container"><div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse"  class="" aria-expanded="true">What's on your minde</a></h4></div><div id="addAnnouncement" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><form id="form-add-announcement" action="#"><div id="div_content"><dt>Say It:</dt><dd><textarea aria-expanded="true" id="addannounce" class="form-control" name="addannounce" placeholder="What you have to say?" title="Chichat" required=""></textarea></dd></div><br><div class="box-footer"><input type="submit" value="Publish" id="submbtn" class="btn pull-right btn-{{ProfileController::get_class()}}"></div></form></div></div></div> </div></div></div>
	<div id="announcement_container"></div>
</div>


<div class='col-md-4'>
	<div class="box box-{{ProfileController::get_class()}} box-solid">
		<div class="box-header with-border">
        	<h3 class="box-title">News Feeds</h3>
		</div><!-- /.box-header -->
		
	</div>
</div>
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
	        edit_form='<div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" href="#EditAnnouncement'+edit_data.announcement[0].id+'" class="" aria-expanded="true">Edit Announcement</a></h4></div><div id="EditAnnouncement'+edit_data.announcement[0].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+edit_data.current_user.profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+edit_data.current_user.user_name+'</p></div></div><form id="edit_form" action="#"><dt>Chichat: </dt><dd><textarea id="form_content" class="form-control">'+edit_data.announcement[0].content+'</textarea></dd><br><input type="submit" value="done" id="edit_btn" class="btn btn-{{ProfileController::get_class()}} btn-block btn-lg"></form></div></div></div></div></div>';
	        $('.edited_div').prepend(edit_form);
	        edit_form ="";
	        $("#edit_btn").click(function(e) {
	            e.preventDefault();
	            var content=$('#form_content').val();
	            var title="test";
	            url='/Announcement/edite-announcement/'+index;
	            var edit_data="";
	            $(this).closest('.box-group').remove();
	            $.post(url,{content:content,title:title},function(done_data){
	                console.log(done_data)
	                edit_data='<div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><button title="Delete" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+done_data.id+'" onclick="Delete(this)">X</button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+done_data.id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button><button title="Edit" class="pull-right btn btn-xs btn-{{ProfileController::get_class()}}" style="margin-right:3px;margin-top:3px;" id="'+done_data.id+'" onclick="Edit(this)"><i class="fa fa-pencil-square-o fa-2"></i></button></div><div id="Announcement'+done_data.id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+done_data.edit_user.profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+done_data.edit_user.user_name+'</p><p style="color:#CCC;font-size:12px">few second ago</p></div></div><div id="announcement_content">'+content+'</div></div></div></div></div></div>';
	                $('.edited_div').append(edit_data);
	                edit_data ="";
	                $('.edited_div').removeClass('edited_div');
	                $.notify("Edited Successfully", "success");
	            })//end of post edit
	        });//end done btn click
	    });//end of edit get request
	}

	var last_announcement_id = 0;
	$.get('announcements/max-announcement-id',{},function(m_data){
        last_announcement_id = m_data.id;
		$.get('/chichat/GetAll',{last_announcement_id : last_announcement_id},function(data){
			console.log(data.announcement);
			console.log(data.at)
			console.log(data.date)
            for(i=0;i<data.announcement.length;i++){
                if(data.at[i]==0){
                  var date=data.date[i];
                }else{
                  var date=data.date[i]+" at " +data.at[i]
                }
                if (data.announcement[i].user_code == {{Auth::user()->user_code}}) {
                    text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><button title="Delete" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'" onclick="Delete(this)">X</button><button title="Edit" class="pull-right btn btn-xs btn-{{ProfileController::get_class()}}" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'" onclick="Edit(this)"><i class="fa fa-pencil-square-o fa-2"></i></button><button title="Comment" class="comment pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'"><i class="fa fa-comments-o"></i></button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].announcement_id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcement[i].announcement_id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcement[i].profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.announcement[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                    $("#announcement_container").append(text);
                    last_announcement_id = data.announcement[i].announcement_id;
                }
                else{
                    text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><button title="Comment" class="comment pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'"><i class="fa fa-comments-o"></i></button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].announcement_id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcement[i].announcement_id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcement[i].profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.announcement[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                    $("#announcement_container").append(text);
                    last_announcement_id = data.announcement[i].announcement_id;
                }//end else
            }//end for loop
        }); // end level get announcement
	});
	var length = -2;	
	$(window).scroll(function () {
        if ($(document).height() <= $(window).scrollTop() + $(window).height()) {
          	last_announcement_id--;
	        if (length == -1){}
	        else if(length != 0){
		       $.get('/chichat/GetAll',{last_announcement_id : last_announcement_id},function(data){
					length = data.announcement.length;
					for(i=0;i<data.announcement.length;i++){
						if(data.at[i]==0){
			          		var date=data.date[i];
			        	}else{
			          		var date=data.date[i]+" at " +data.at[i]
			        	}
						if(data.announcement[i].user_code == {{Auth::user()->user_code}}) {
                    		text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><button title="Delete" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'" onclick="Delete(this)">X</button><button title="Edit" class="pull-right btn btn-xs btn-{{ProfileController::get_class()}}" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'" onclick="Edit(this)"><i class="fa fa-pencil-square-o fa-2"></i></button><button title="Comment" class="comment pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'"><i class="fa fa-comments-o"></i></button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].announcement_id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcement[i].announcement_id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcement[i].profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.announcement[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                    		$("#announcement_container").append(text);
                    		last_announcement_id = data.announcement[i].announcement_id;
                    		text = "";
                		}
                		else{
                    		text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><button title="Comment" class="comment pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+data.announcement[i].announcement_id+'"><i class="fa fa-comments-o"></i></button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcement[i].announcement_id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcement[i].announcement_id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcement[i].profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+data.announcement[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="announcement_content">'+data.announcement[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcement[i].like+' People winked this</div></div></div></div></div></div></div>';
                    		$("#announcement_container").append(text);
                    		last_announcement_id = data.announcement[i].announcement_id;
                    		text = "";
               			}//end else
					}//end of for loop
		  		});//end announcement get
	        }//end if last id != 0
	        
	        else{
	        	text = '<div class="box-footer text-center"><p>There is No More Chichat</p></div>';
	        	$("#announcement_container").append(text);
	        	text = "";
	        	length = -1;
	        }
	        
	        
	        
        }//end of if statment
    });//end of windows scroll










	$("#form-add-announcement").submit(function(e){
        e.preventDefault();
        var content=$('#addannounce').val();
        var title= 'test';
        var groupselection="ChooseGroup";
        var levelselection="ChooseLevel";
        $.post('/Announcement/add-announcement',{content:content,title:title,groupselection:groupselection,levelselection:levelselection},function(r_data){
            var add_text="";
            add_text='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><button title="Delete" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+r_data.id+'" onclick="Delete(this)">X</button><button title="Edit" class="pull-right btn btn-xs btn-{{ProfileController::get_class()}}" style="margin-right:3px;margin-top:3px;" id="'+r_data.id+'" onclick="Edit(this)"><i class="fa fa-pencil-square-o fa-2"></i></button><button title="Comment" class="comment pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;" id="'+r_data.id+'"><i class="fa fa-comments-o"></i></button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+r_data.id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+r_data.id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+r_data.profile_picture+'" class="img-circle" alt="User Image" id="user_img"></div><div class="pull-left info" style="color:#000"><p>'+r_data.user+'</p><p style="color:#CCC;font-size:12px">few second ago</p></div></div><div id="announcement_content">'+content+'</div></div></div></div></div></div></div>';
            $("#announcement_container").prepend(add_text);
            add_text="";
            document.getElementById("form-add-announcement").reset();
            $.notify("Success in Add Announcement", "success");
        });//end of post announcement
    });//end of form submite
</script>
@endsection
@stop

