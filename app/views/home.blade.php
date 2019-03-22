@extends('layout.mainTheme')

@section('content')
<div class='col-md-8'>
	<div class="nav-tabs-custom">
	    <ul class="nav nav-tabs">
		    <li class="active"><a href="#" data-toggle="tab" id="announcement_link">Announcements</a></li>
		    <li><a href="#" data-toggle="tab" id="vote_link">Votes</a></li>
		    @if(Auth::user()->profession != 'employee')
		    <li><a href="#" data-toggle="tab" id="question_link">Questions</a></li>
		    <li><a href="#" data-toggle="tab" id="assignment_link">Assignments</a></li>
		    
		    <li><a href="#" data-toggle="tab" id="quize_link">quizzes</a></li>
		    @endif
		</ul>
		<div class="tab-content">
	        <div  class="tab-pane active" id='announcements'><div id="announcement_container"></div></div>      
	        <div  class="tab-pane" id='questions'><div id="question_container"></div></div>
	        <div  class="tab-pane" id='assignments'><div id="assignment_container"></div></div>
	        <div  class="tab-pane" id='votes'><div id="vote_container"></div></div>
	        <div  class="tab-pane" id='quizzes'><div id="quiz_container"></div></div>
	    </div>
	</div>
</div>

<div class='col-md-4'>
	<div class="box box-{{ProfileController::get_class()}} box-solid">
		<div class="box-header with-border">
        	<h3 class="box-title">Home Feeds</h3>
		</div><!-- /.box-header -->
		<div class="box-body" style="background:#efefef;">
        	<div class="box box-{{ProfileController::get_class()}}">
            	<div class="box-header with-border">
                	<h3 class="box-title">Important Events</h3>
                </div><!-- /.box-header -->
				<div class="box-body">
                	<div class="qContent panel">danger events added in calender</div>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
            <div class="box box-{{ProfileController::get_class()}}">
            	<div class="box-header with-border">
                	<h3 class="box-title">Friend requests</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
                	<div class="qContent panel">new friend requests</div>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
            <div class="box box-{{ProfileController::get_class()}}">
            	<div class="box-header with-border">
                	<h3 class="box-title">New Message</h3>
				</div><!-- /.box-header -->
                <div class="box-body">
                	<div class="qContent panel">new messages from friends</div>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
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
$(document).ready(function() {
	var text="";
	var last_announcement_id_group = 0;
	var last_announcement_id_level = 0;
	var last_question_id = 0;
	var last_vote_id = 0;
	var last_quiz_id = 0;
	$.get('announcements/max-announcement-id',{},function(m_data){
      	last_announcement_id_group = m_data.id;
      	last_announcement_id_level = m_data.id;
    	$.get('/Announcement/show-home-announcement',{'announcement_id_level':last_announcement_id_level,'announcement_id_group' : last_announcement_id_group},function(data){
    		console.log(data.announcements)
			for(i=0;i<data.announcements.length;i++){
				if(data.at[i]==0){
	          		var date=data.date[i];
	        	}else{
	          		var date=data.date[i]+" at " +data.at[i]
	        	}
				if(data.announcements[i].checks==1){
					text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Announcement'+data.announcements[i].id+'" class="" aria-expanded="true">'+data.announcements[i].title+'</a></h4><button title="Announcement for Level'+data.announcements[i].level+'" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;">L'+data.announcements[i].level+'</button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcements[i].id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcements[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcements[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.announcements[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="Announcement_content" class = "qContent">'+data.announcements[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcements[i].like+' People winked this</div></div></div></div></div></div></div>';
					last_announcement_id_level = data.announcements[i].id;
				}else if(data.announcements[i].checks==0){
					text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Announcement'+data.announcements[i].id+'" class="" aria-expanded="true">'+data.announcements[i].title+'</a></h4><a href="/group/show/'+data.announcements[i].group_name+' #/announcements"><button title="Announcement for group '+data.announcements[i].group_name+'" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;">G</button></a><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcements[i].id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcements[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcements[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.announcements[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="Announcement_content">'+data.announcements[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcements[i].like+' People winked this</div></div></div></div></div></div></div>';
					last_announcement_id_group = data.announcements[i].id;
				}
				
			}
			$("#announcement_container").append(text);
			text="";
  		});//end announcement get
	});//end max announcement get
	if ('{{Auth::user()->profession}}' != 'employee') {
	$.get('questions/max-question-id',{},function(m_data){
		console.log(m_data.max_id);
		last_question_id = m_data.max_id;
		$.get('/Questions/show-home-question',{'last_question_id':last_question_id},function(data){
			for (var i = 0; i < data.questions.length; i++) {
				if(data.at[i]==0){
		          var date=data.date[i];
		        }else{
		          var date=data.date[i]+" at " +data.at[i]
		        }
				text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Question'+data.questions[i].id+'" class="" aria-expanded="true">'+data.questions[i].title+'</a></h4><a href="/group/show/'+data.questions[i].name+'#/questionDetails/'+data.questions[i].id+'"><button title="Question for group '+data.questions[i].name+'" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;">G</button></a></div><div id="Question'+data.questions[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="question"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.questions[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.questions[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="Question_content">'+data.questions[i].content+'</div><div id="question_search_tag">'+data.questions[i].search_tag+'</div></div></div></div></div></div></div>';
				last_question_id = data.questions[i].id;
			};
			$("#question_container").append(text);
			text="";
		});//end question get
	});//end of max question get
	
}
	$.get('votes/max-vote-id',{},function(m_data){
		console.log(m_data.max_id);
		last_vote_id = m_data.max_id;
		$.get('/Votes/show-home-vote',{'last_vote_id':last_vote_id},function(data){
			console.log(data.votes)
			for (var i = 0; i < data.votes.length; i++) {
				if(data.at[i]==0){
		          var date=data.date[i];
		        }else{
		          var date=data.date[i]+" at " +data.at[i]
		        }
				text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Vote'+data.votes[i].id+'" class="" aria-expanded="true">'+data.votes[i].title+'</a></h4><a href="/group/show/'+data.votes[i].name+'#/VoteList"><button title="Vote for group '+data.votes[i].name+'" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;">G</button></a></div><div id="Vote'+data.votes[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="vote"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.votes[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.votes[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="vote_content">'+data.votes[i].content+'</div></div></div></div></div></div></div>';
				last_vote_id = data.votes[i].id;
			};
			$("#vote_container").append(text);
			text="";
		});//end vote get
	})
	if ('{{Auth::user()->profession}}' != 'employee') {
	$.get('Quizzes/max-quiz-id',{},function(m_data){
		console.log(m_data.max_id);
		last_quiz_id = m_data.max_id;
		$.get('Quizzes/show-home-quiz',{'last_quiz_id':last_quiz_id},function(data){
			for (var i = 0; i < data.quizzes.length; i++) {
				if(data.at[i]==0){
		          var date=data.date[i];
		        }else{
		          var date=data.date[i]+" at " +data.at[i]
		        }
				text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Quiz'+data.quizzes[i].id+'" class="" aria-expanded="true">'+data.quizzes[i].quiz_name+'</a></h4><a href="/group/show/'+data.quizzes[i].group_name+'#/quizzes"><button title="Quiz for group '+data.quizzes[i].group_name+'" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;">G</button></a></div><div id="Quiz'+data.quizzes[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="quiz"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.quizzes[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.quizzes[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div></div></div></div></div></div></div>';
				last_quiz_id = data.quizzes[i].id;
			};
			$("#quiz_container").append(text);
			text="";
		});//end quiz get
	});
	}
	$(window).scroll(function () {
        if ($(document).height() <= $(window).scrollTop() + $(window).height()) {
          	last_question_id--;
	        last_announcement_id_level--;
	        last_announcement_id_group--;
	        last_vote_id--;
	        last_quiz_id--;
	        
	        if(last_announcement_id_group != 0 && last_announcement_id_level != 0){
		        $.get('/Announcement/show-home-announcement',{'announcement_id_level':last_announcement_id_level,'announcement_id_group' : last_announcement_id_group},function(data){
		        	console.log(data.test)
		        	console.log(data.test2)
					
					for(i=0;i<data.announcements.length;i++){
						if(data.at[i]==0){
			          		var date=data.date[i];
			        	}else{
			          		var date=data.date[i]+" at " +data.at[i]
			        	}
						if(data.announcements[i].checks==1){
							console.log(data.announcements[i].check)
							text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Announcement'+data.announcements[i].id+'" class="" aria-expanded="true">'+data.announcements[i].title+'</a></h4><button title="Announcement for Level'+data.announcements[i].level+'" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;">L'+data.announcements[i].level+'</button><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcements[i].id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcements[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcements[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.announcements[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="Announcement_content">'+data.announcements[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcements[i].like+' People winked this</div></div></div></div></div></div></div>';
							last_announcement_id_level = data.announcements[i].id;
						}else if(data.announcements[i].checks==0)
						{
							console.log(data.announcements[i].check)
							text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Announcement'+data.announcements[i].id+'" class="" aria-expanded="true">'+data.announcements[i].title+'</a></h4><a href="/group/show/'+data.announcements[i].group_name+' #/announcements"><button title="Announcement for group '+data.announcements[i].group_name+'" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;">G</button></a><button title="Wink" class="pull-right btn btn-xs btn-success" style="margin-top:3px;" onclick="Like('+data.announcements[i].id+',{{Auth::user()->id}})"><i class="fa fa-eye"></i></button></div><div id="Announcement'+data.announcements[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="announcement"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.announcements[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.announcements[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="Announcement_content">'+data.announcements[i].content+'</div><div style="color:#CCC;font-size:12px">'+data.announcements[i].like+' People winked this</div></div></div></div></div></div></div>';
							last_announcement_id_group = data.announcements[i].id;
						}//end else if (check)
					}//end of for loop
					$("#announcement_container").append(text);
					text="";
		  		});//end announcement get
	        }//end if last id != 0
	        
	        else{
	        	text = '<div class="box-footer text-center"><p>There is No More Announcement</p></div>';
	        	$("#announcement_container").append(text);
	        	text = "";
	        	length = -1;
	        }
	        if (last_question_id != 0) {
	        	$.get('/Questions/show-home-question',{'last_question_id':last_question_id},function(data){
					for (var i = 0; i < data.questions.length; i++) {
						if(data.at[i]==0){
				          var date=data.date[i];
				        }else{
				          var date=data.date[i]+" at " +data.at[i]
				        }
						text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Question'+data.questions[i].id+'" class="" aria-expanded="true">'+data.questions[i].title+'</a></h4><a href="/group/show/'+data.questions[i].name+'#/questionDetails/'+data.questions[i].id+'"><button title="Question for group '+data.questions[i].name+'" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;">G</button></a></div><div id="Question'+data.questions[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="question"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.questions[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.questions[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="Question_content">'+data.questions[i].content+'</div><div id="question_search_tag">'+data.questions[i].search_tag+'</div></div></div></div></div></div></div>';
						last_question_id = data.questions[i].id;
					};
					$("#question_container").append(text);
					text="";
				});//end question get
	        }//end of if last id != 0
	        else{
	        	text = '<div class="box-footer text-center"><p>There is No More Question</p></div>';
	        	$("#question_container").append(text);
	        	text="";
	        } 
	        if(last_vote_id != 0){
	        	$.get('/Votes/show-home-vote',{'last_vote_id':last_vote_id},function(data){
					console.log(data.votes)
					for (var i = 0; i < data.votes.length; i++) {
						if(data.at[i]==0){
				          var date=data.date[i];
				        }else{
				          var date=data.date[i]+" at " +data.at[i]
				        }
						text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Vote'+data.votes[i].id+'" class="" aria-expanded="true">'+data.votes[i].title+'</a></h4><a href="/group/show/'+data.votes[i].name+'#/VoteList"><button title="Vote for group '+data.votes[i].name+'" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;">G</button></a></div><div id="Vote'+data.votes[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="vote"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.votes[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.votes[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="vote_content">'+data.votes[i].content+'</div></div></div></div></div></div></div>';
						last_vote_id = data.votes[i].id;
					};
					$("#vote_container").append(text);
					text="";
				});//end vote get
	    	}//end of if last id != 0
	    	else{
	        	text = '<div class="box-footer text-center"><p>There is No More Votes</p></div>';
	        	$("#vote_container").append(text);
	        	text="";
	        }
	        if (last_quiz_id != 0) {
	        	$.get('Quizzes/show-home-quiz',{'last_quiz_id':last_quiz_id},function(data){
					for (var i = 0; i < data.quizzes.length; i++) {
						if(data.at[i]==0){
				          var date=data.date[i];
				        }else{
				          var date=data.date[i]+" at " +data.at[i]
				        }
						text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Quiz'+data.quizzes[i].id+'" class="" aria-expanded="true">'+data.quizzes[i].quiz_name+'</a></h4><a href="/group/show/'+data.quizzes[i].group_name+'#/quizzes"><button title="Quiz for group '+data.quizzes[i].group_name+'" class="pull-right btn btn-{{ProfileController::get_class()}} btn-xs" style="margin-left:3px;margin-top:3px;">G</button></a></div><div id="Quiz'+data.quizzes[i].id+'" class="panel-collapse collapse in" aria-expanded="true"><div class="box-body"><div id="quiz"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.quizzes[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.quizzes[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div></div></div></div></div></div></div>';
						last_quiz_id = data.quizzes[i].id;
					};
					$("#quiz_container").append(text);
					text="";
				});//end quiz get
	        }
	        else{
	        	text = '<div class="box-footer text-center"><p>There is No More Quizzes</p></div>';
	        	$("#quiz_container").append(text);
	        	text="";
	        }
        }//end of if statment
    });//end of windows scroll
	if ('{{Auth::user()->profession}}' != 'employee') {
	$.get('/Assignments/show-home-Assignment',{},function(data){
		console.log(data.assignments)
		for (var i = 0; i < data.assignments.length; i++) {
			if(data.at[i]==0){
	          var date=data.date[i];
	        }else{
	          var date=data.date[i]+" at " +data.at[i]
	        }
			text+='<div class="box-body"><div class="box-group" id="accordion"><div class="panel box box-{{ProfileController::get_class()}}"><div class="box-header with-border"><h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#Assignment'+data.assignments[i].id+'" class="collapsed" aria-expanded="false">'+data.assignments[i].title+'</a></h4></div><div id="Assignment'+data.assignments[i].id+'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;"><div class="box-body"><div id="assignment"><div class="user-panel"><div class="pull-left image"><img src="../../images/'+data.assignments[i].profile_picture+'" class="img-circle" alt="User Image"></div><div class="pull-left info" style="color:#000"><p>'+data.assignments[i].user_name+'</p><p style="color:#CCC;font-size:12px">'+date+'</p></div></div><div id="assignment_for">Assignment for Group <a href="/group/show/'+data.assignments[i].group_name+'#/assignments">'+data.assignments[i].group_name+'</a></div><div id="assignment_content">'+data.assignments[i].content+'</div></div></div></div></div></div></div>';
		};
		$("#assignment_container").append(text);
		text="";
	});//end assignment get
	}
	$("#announcement_link").click(function(e){
		e.preventDefault();
		$("#announcements").show();
		$("#questions").hide();
		$("#assignments").hide();
		$("#quizzes").hide();
		$("#votes").hide();
	});

	$("#question_link").click(function(e){
		e.preventDefault();
		$("#announcements").hide();
		$("#questions").show();
		$("#questions").addClass('active');
		$("#assignments").hide();
		$("#quizzes").hide();
		$("#votes").hide();
	});

	$("#assignment_link").click(function(e){
		e.preventDefault();
		$("#announcements").hide();
		$("#questions").hide();
		$("#assignments").addClass('active');
		$("#assignments").show();
		$("#quizzes").hide();
		$("#votes").hide();
	});

	$("#vote_link").click(function(e){
		e.preventDefault();
		$("#votes").addClass('active');
		$("#announcements").hide();
		$("#questions").hide();
		$("#assignments").hide();
		$("#quizzes").hide();
		$("#votes").show();
	});

	$("#quize_link").click(function(e){
		e.preventDefault();
		$("#quizzes").addClass('active');
		$("#announcements").hide();
		$("#questions").hide();
		$("#assignments").hide();
		$("#votes").hide();
		$("#quizzes").show();
	});
});//end of document ready
</script>
@endsection
@stop

