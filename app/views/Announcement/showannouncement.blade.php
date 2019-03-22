@extends('layout.mainTheme')

@section('content')

<form id="form-add-announcement" action="#">
<div class="panal">
    	<label>Title:</label><input type="text" name="title" id="title" placeholder="Add Title To Announcement" required/>
     
    </div>
    <div>
        <label>Announcement:</label><textarea id="addannounce" name="addannounce" placeholder="Type Announcement" required></textarea>
 
        <label>to:</label>
        <select name="groupselection" id="groupselection">
        	<option selected="selected">ChooseGroup</option>
        	@foreach($groups as $index)
            		<option value="{{$index->id}}">{{$index->name}}</option>
            @endforeach
            
        </select>
        <select name="levelselection" id="levelselection">
            <option selected="selected">ChooseLevel</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
        </select>
    </div>
    <div>
    <input type="submit" value="Add Announcement" id="submbtn" >
    </div>	
</form>

<div>
<a href="#" id="show_announcement">My Announcements</a>
<a href="#" id="show_group_announcement">Groups Announcements</a>
<a href="#" id="show-level-announcement">Level Announcements</a>
</div>
<div id="announcement_container"></div>
<div id='announcement_group'></div>
<div id='announcement_level'></div>
{{HTML::script('js/jquery-1.11.2.min.js')}}

<script>

$(document).ready(function(){
	var text="";
	//setInterval(function(){
		$("#announcement_container").empty();
	$.get('show-announcement',{},function(data){
		for(i=0;i<data.announcement.length;i++){
			text="<div id='announcement'><div id='announcement_maker'>"+data.user+"</div><div id='announcement_title'>"+data.announcement[i].title+"</div><div id='announcement_content'>"+data.announcement[i].content+"</div><a href='#' class='delete_announcement' title='"+data.announcement[i].id+"'>Delete</a><a href='#' class='edit_announcement' title='"+data.announcement[i].id+"'>Edit</a></div>";
			$("#announcement_container").prepend(text);
			}
	$(".delete_announcement").click(function(e) {
	  e.preventDefault();
	  var index=$(this).attr('title');
	  console.log(index);
	  url='delete-announcement/'+index;
	  console.log(url);
	  $.get(url,{},function(d_data){});//end of delete get request
	  $(this).parent().remove();
  });//end delete click
  
  
  $(".edit_announcement").click(function(e) {
	  e.preventDefault();
	  var index=$(this).attr('title');
	  console.log(index);
	  url='edit-announcement/'+index;
	  var edit_form="";
	  var self = this;
	  $(self).prevUntil("#announcement_maker").remove();
	  $(self).hide();
	  $.get(url,{},function(edit_data){
		  edit_form="<form id='edit_form' action='#'><label>Title: </label><input type='text' id='form_title' value='"+edit_data.announcement[0].title+"'><label>Announcement: </label><textarea id='form_content'>"+edit_data.announcement[0].content+"</textarea><br><input type='submit' value='done' id='edit_btn'><input type='submit' value='cancel' id='cancel_btn'></form>";
	  $(self).parent().append(edit_form);
	  $("#edit_btn").click(function(e) {
		  e.preventDefault();
		  var content=$('#form_content').val();
		  var title=$('#form_title').val();
		  url='edite-announcement/'+index;
		  var edit_data="";
		  $.post(url,{content:content,title:title},function(done_data){
				edit_data="<div id='announcement_title'>"+title+"</div><div id='announcement_content'>"+content+"</div><a href='#' id='delete_announcement' title='"+index+"'>Delete</a><a href='#' id='edit_announcement' title='"+index+"'>Edit</a></div>";
				$('#edit_form').hide();
				$('#edit_form').parent().append(edit_data);
				})//end of post edit
			});//end done btn click
		});//end of edit get request 
	});//end of edit link click
});//end of get announcement content
	//},5000);
	
$("#form-add-announcement").submit(function(e){
	e.preventDefault();
	var content=$('#addannounce').val();
	var title=$('#title').val();
	var groupselection=$('#groupselection').val();
	var levelselection=$('#levelselection').val();
	$.post('add-announcement',{
	content:content,
	title:title,
	groupselection:groupselection,
	levelselection:levelselection
	},function(r_data){
		var add_text="";
		add_text="<div id='announcement'><div id='announcement_maker'>"+r_data.user+"</div><div id='announcement_title'>"+title+"</div><div id='announcement_content'>"+content+"</div><a class='delete' title='"+r_data.id+"'>Delete</a></div>";
		$("#announcement_container").prepend(add_text);
		add_text="";
		document.getElementById("form-add-announcement").reset();
		$(".delete").click(function(e) {
			e.preventDefault();
			var index=$(this).attr('title');
			console.log(index);
			url='delete-announcement/'+index;
			console.log(url);
			$.get(url,{},function(d_data){});//end of delete get request
			$(this).parent().remove();
		});//end delete click
	});//end of post announcement
});//end of form submite

$("#show_group_announcement").click(function(e) {
	e.preventDefault();
	$.get('show-group-announcement',{},
	function(data){
		var i;
		var text="";
		$("#announcement_container").hide();
		$("#announcement_level").hide();
		$("#announcement_group").show();
		$("#announcement_group").empty();
		for(i=0;i<data.result.length;i++){
			if(i==0||data.result[i-1]=='//'){
					text+="<fieldset><legend>"+data.result[i]+"</legend>";
				}
			else if(data.result[i]=='//');
			else{
				text+="<div id='name'>"+data.result[i]+"</div></fieldset>"	
				}//end else
				
			}//end for
			$("#announcement_group").prepend(text);
			text="";
		}//end function data
	);
});
$("#show_announcement").click(function(e) {
            e.preventDefault();
			$("#announcement_group").hide();
			$("#announcement_level").hide();
			$("#announcement_container").show();
        });		
		
	$("#show-level-announcement").click(function(e) {
        e.preventDefault();
		$.get('show-level-announcement',{},function(data){
			console.log(data.level)
			console.log(data.user)
			console.log(data.announcements)
			$("#announcement_group").hide();
			$("#announcement_container").hide();
			$("#announcement_level").show();
			$("#announcement_level").empty();
			var text="<div id='level_name'>Level "+data.level+"</div>"
			var i;
			for(i=0;i<data.user.length;i++){
				text+="<div id='announcemnt'><div id='announcement_maker'>"+data.user[i].user_name+"</div><div id='announcement_title'>"+data.announcements[i][0].title+"</div><div id='announcement_content'>"+data.announcements[i][0].content+"</div></div>"
			}
			$("#announcement_level").prepend(text);
			text="";
		});
    });//end show level click
});//end document ready
</script>
@endsection
@stop




