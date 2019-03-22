
@if($IsAdmin != NULL)
<!--Note : - Insert new-->
<div class="box box-primary box-solid" id="Add_Form">
		<script type="text/javascript">
			function Change_Date_Format() {
			var dt1 = new Date();
			var due = document.getElementById("datetimepicker").value;//HERE
			var dt2 = new Date(due);
			var diff = dt2.getTime() - dt1.getTime();
			if (diff>0)
			{
			var temp = diff / 1000;
			temp /= 60;
			var minutes = Math.ceil(temp % 60);
			temp /= 60;
			var hours = Math.ceil(temp % 24);
			temp /= 24;
			var days = Math.ceil(temp);
			var date = days+" days "+hours+" hours "+minutes+" minutes";
			return date;
			}
			else return "None";
    		}
		   function saveDate() {
		   var d = document.getElementById("datetimepicker").value;
		   var date = new Date(d) ;
		                          /* 2015-04-02 17:08:37 */
		   var dueDate = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate() + " " +
		                          date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds() ;
		   document.getElementById('DueDate').value = dueDate;
		  }
		  /*|Upload a new assignment                    |
		    |Triggers the google drive api functionality|
		    |Shows the upload form                      |*/
		  function createNew() {
		    var addForm = document.getElementById("Add_Assignment");
		      if(addForm.style.display == "block" ) {
		              addForm.style.display = "none";
		        }
		      else {
		            //document.getElementById('resultName').value = "Loading File Upload";
		           // var my_awesome_script = document.createElement('script');
		           // my_awesome_script.setAttribute('src','https://apis.google.com/js/api.js?onload=onApiLoad');
		            //document.head.appendChild(my_awesome_script);
		            addForm.style.display = "block";
		      }
		  }
		</script>
	     <div id="Add_Button" onclick="createNew()">
	     	<div class="form-container">
	     	<div class="box-header with-border">
	     	<h3 class="box-title">Add Assignment</h3>
	     	</div>
	     	</div>
	     </div>
	    <div class="box-body" id="Add_Assignment" style="display:none">
	      <div class="form-container">
	      <form  method="post" id="">
	       <div class="form-group">
	       	<h4 class="box-title">Title :</h4>
	        <input type='text' class="form-control"  id="resultName" name="resultName"  required="" placeholder="title"/>
	       </div>
	   	   </br>
	   	   <div class="form-group">
	       	<h4 class="box-title">Link :</h4>
	        <input type='text' class="form-control"  id="resultUrl" name="resultUrl"  required="" placeholder="Insert url of file"/>
	       </div>
	   	   </br>
	   	   <div class="form-group">
	       	<h4 class="box-title">Information :</h4>
	        <input type='text' class="form-control"  id="resultContent" name="resultContent"  required="" placeholder="Anything you want to say about the assignment ?"/>
		    </div>
	   	   </br>
	       <div class="form-group">
	        <h4 class="box-title">Due :</h4>
	        <input type="date" id="datetimepicker" onchange="saveDate()"/>
	        <input id="DueDate" name="DueDate" type="hidden">
	       </div>
	    <!--   <input type="hidden" id="resultUrl" name="resultUrl">-->
	       <div class="box-footer clearfix">
	        <input onclick='add_assignment()'  class="btn btn-{{ProfileController::get_class()}} pull-right btn  " type="submit" value="Add Assignment">
	        {{ Form::token() }}
	       </div>
	      </form>
	    </div></div>
</div>
@endif
<!--Note : - View Cats-->
<div id="Listing form" class="box box-primary box-solid">
<div class="form-container">
<div class="box-header with-border">
	    <form action="{{  URL::route('category-assignment') }}" method="post" id="">
	    		<div class="pull-left"><h3 class="box-title">Assignments {{$category}} :</h3></div>
	    		<div class="pull-left" style="padding-left: 7%">
	    		<h3 class="box-title">Select :</h3>
			    <select style='color:#000;' id="target_assignment" name="SelectBy" onchange="show()">
			      <option value=0 ></option>
			      <option value=1 >Ongoing</option>
			      <option value=2 >Reached Deadline</option>
			      @if($IsAdmin != NULL)
			      <option value=3 >Uploaded By Me</option>
			      <option value=4 >Uploaded By Others</option>
			      @endif
			    </select>
			    </div>
		</form>
</div>
</div>
<div class="box-body" id="show_Assignment">
	    	@if(!$isEmpty)
	    	@foreach($assignmentsList as $assignment)
	    	<?php
				//date check
				$date1=date_create($assignment->due_date);
				$date2=date_create(date("Y-m-d H:i:s"));
				$TimeRemain=strtotime($assignment->due_date)-strtotime(date("Y-m-d H:i:s"));
				$diff=date_diff($date1,$date2);
				?>
	    		<div class="panel box box-primary"><!--assignment div info-->
	    		<div class="box-header with-border" >
	    			<h4 class="box-title">{{ $assignment->title }}</h4>
	    		</div>
	    		<div class="box-body">
			    <table width=100% cellpadding="10">
			    	<tr>
			    		<th style="text-align: left">
			   				<h4 class="box-title">Link :</h4><h4 class="box-title">{{ HTML::link('https://'.$assignment->link, 'Click here',array('target'=>'_blank'))}}</h4>
			    		</th>
			    		<th style="text-align: left">
			    			<h4 class="box-title">Due :</h4>
			    			<h4 class="box-title">{{$assignment->due_date}}</h4>		
			    		</th>
			    		<th style="text-align: left">
			    			<h4 class="box-title">Time Remaining :</h4>
				   			<h4 class="box-title">
							@if($TimeRemain>0)
								{{$diff->format('%d days %h hours %i minutes')}}
				    		@else
				    			None .
							@endif
			  				</h4>
						</th>
			    	</tr>
			    	<tr>
			    			<td colspan=4 style="text-align: left" ></br>
			    				<h4 class="box-title">Information : </br>
			    				@if($assignment->content!=NULL){{$assignment->content}}
			    				@else Nothing here
			    				@endif
			    				</h4>
			    			</td>
			    		</tr>
					</table>
					</div>
				</div><!--end assignment div info-->
	    	@endforeach
	    	@else
	    	<div class="panel box box-primary">
	    		<div class="box-header with-border" >
	    			<h4 class="box-title">Nothing Here!</h4>
	    		</div>
        	</div>
   			@endif
</div>
</div>
<script type="text/javascript">
//reached
function add_assignment(){
var a_title=$("#resultName").val();
var a_url=$("#resultUrl").val();
var a_content=$("#resultContent").val();
var a_dueDate=$("#datetimepicker").val();


if(a_title =="" || a_content=="" || a_url=="" || a_dueDate==""){
alert("All Fields are required","error");

}else{
//Note: aya
var dt1 = new Date();
var dt2 = new Date(a_dueDate);
var diff = dt2.getTime() - dt1.getTime();
if (diff<0){alert("Deadline reached","error");}
else{
//end note aya
var assignment={
	title:a_title,
	link:a_url,
	due_date:a_dueDate,
	content:a_content,
	group_id:group.id
}
//note
alert(group.id);
console.log(assignment);

$.ajax({
              type: "POST",
              url: '/Assignments/'+group.id,
              data:{data:assignment},
              success:function(data){
              	alert("Assignment Successfully Added");
              	if($("#target_assignment option:selected").index()==1||$("#target_assignment option:selected").index()==0){
				$("#show_Assignment").prepend('<div class="panel box box-primary"><div class="box-header with-border" ><h4 class="box-title">'+assignment.title+'</h4></div><div class="box-body"><table width=100% cellpadding="10"><tr><th style="text-align: left"><h4 class="box-title">Link :</h4><h4 class="box-title"><a href="'+assignment.link+'" target="_blank">Click here</a></h4></th><th style="text-align: left"><h4 class="box-title">Due :</h4><h4 class="box-title">'+assignment.due_date+'</h4></th><th style="text-align: left"><h4 class="box-title">Time Remaining :</h4><h4>'+Change_Date_Format()+'</h4></th></tr><tr><td colspan=4 style="text-align: left" ></br><h4 class="box-title">Information : </br>'+assignment.content+'</h4></td></tr></table></div></div>');
			    }
			    $("#resultName").val("");
				$("#resultUrl").val("");
				$("#resultContent").val("");
				$("#datetimepicker").val("");//'+assignment.title+' HERE
				$("#Add_Assignment").hide();
			    },


              error:function(data){
                console.log(data);
              },
              beforeSend:function(){
              }
            });

}
return false;}
}
function show(){
var target= $("#target_assignment").val();
alert(target);
window.location.href="#/filter/assignments/"+target;
}
</script>
