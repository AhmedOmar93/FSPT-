@extends('layout.mainTheme')
@section('content')
<script>
		<!--Google Drive Api-->
		{{ HTML::script('https://apis.google.com/js/api.js?onload=onApiLoad') }}
		{{ HTML::script('js/GoogleDriveApiUploadPicker.js') }}
		<!--Date/Time Picker-->
		{{ HTML::style('css/datetimePicker/datetimePicker1.css') }}
		{{ HTML::style('http://cdn.kendostatic.com/2014.3.1411/styles/kendo.default.min.css') }}
		{{ HTML::style('css/datetimePicker/datetimePicker3.css') }}
		{{ HTML::style('css/datetimePicker/datetimePicker4.css') }}
		{{ HTML::script('http://cdn.kendostatic.com/2014.3.1411/js/jquery.min.js') }}
		{{ HTML::script('http://cdn.kendostatic.com/2014.3.1411/js/kendo.all.min.js') }}
</script>
<script type="text/javascript">
<!---->
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
		 document.getElementById('resultName').value = "Loading File Upload";
		 var my_awesome_script = document.createElement('script');
		 my_awesome_script.setAttribute('src','https://apis.google.com/js/api.js?onload=onApiLoad');
		 document.head.appendChild(my_awesome_script);
		 addForm.style.display = "block";
	     document.getElementById('addFormActivated').value = "true";
		}
		function showUpdateDiv(){
		 var addForm = document.getElementById("Other_Form");
		 if(addForm.style.display == "block" ) {
		   addForm.style.display = "none";
		 }
		 else {
			addForm.style.display = "block";
			}
		}
</script>
@foreach($More as $more)
<?php
//date check
$date1=date_create($more->due_date);
$date2=date_create(date("Y-m-d H:i:s"));
$TimeRemain=strtotime($more->due_date)-strtotime(date("Y-m-d H:i:s"));
$diff=date_diff($date1,$date2);
?>
<div class="box box-primary box-solid" id="Show_Form"><!--Note : - View More-->
	<div class="box-header"><!--Header-->
		<a target="_blank" href="{{ $more->link }}"><h3 class="box-title">Title : {{ $more->title }}</h3></a> 
	</div>
	<div class="box-body" id="show_Assignment"><!--Info-->
		<div class="panel box box-primary" >
	    <div class="box-header with-border" >
	    	<table width=100% cellpadding="10">
	    		<tr>
	    			<th style="text-align: left">
	   				<h3 class="box-title">Link :</h3></br>
	    			<h3 class="box-title"><a target="_blank" href="{{ $more->link }}">Click here</a></h3>
	    		</th>
	    		<th style="text-align: left">
	    			<h3 class="box-title">Due :</h3></br>
	    			<h3 class="box-title">{{$more->due_date}}</h3>		
	    		</th>
	    		<th style="text-align: left">
	    			<h3 class="box-title">Time Remaining :</h3></br>
		   			<h3 class="box-title">
					@if($TimeRemain>0)
						{{$diff->format('%d days %h hours %i minutes')}}
		    		@else
		    			None .
					@endif
	  				</h3>
				</th>
	    				<!--<th style="text-align: left">
	    					<h3 class="box-title">Uploaded by :</h3></br>
	    					<h3 class="box-title">/**/</h3>
	    				</th>-->
	    		</tr>
	    		<tr>
	    			<td colspan=4 style="text-align: left" ></br>
	    				<h3 class="box-title">Information : </br>
	    				@if($more->content!=NULL){{$more->content}}
	    				@else Nothing here
	    				@endif
	    				</h3>
	    			</td>
	    		</tr>
			</table>
		</div>
	    </div>
	</div>
</div>
<div class="box box-primary box-solid" ><!--Note : - Manipulation-->
		<div class="box-header" onclick="showUpdateDiv()"><!--Header-->
		@if($IsAdmin != NULL)<!--Note : - Admin-->
			@if($TimeRemain>0)
			<h3 class="box-title">Update</h3>
			@else
			<h3 class="box-title">Solutions</h3>
			@endif
			@else
			@if($TimeRemain>0)
			<h3 class="box-title">Update My Solution</h3>
			@else
			<h3 class="box-title">View Grade</h3>
			@endif
		@endif
	</div>
	<div class="box-body" id="Other_Form" style="display:none">
		@if($IsAdmin != NULL)<!--Note : - Admin:Assignment Manipulation-->
			@if($TimeRemain>0)
			<form action="{{  URL::route('update-assignment',$more->id) }}" method="post" id="">
				<div class="form-group">
		       	<h3 class="box-title">
		       	Title :</h3>
		        <input type='text' class="form-control"  id="resultName" name="resultName"  required="" placeholder="{{ $more->title }}"/>
		       </div>
		   	   </br>
		   	   <div class="form-group">
		       	<h3 class="box-title">
		       	Information :</h3>
		        <input type='text' class="form-control"  id="resultContent" name="resultContent"  required="" placeholder="{{ $more->content }}"/>
		       </div>
		   	   </br>
		       <div class="form-group">
		        {{ HTML::script('js/datetimePicker/datetimePicker1.js') }}
		        {{ HTML::style('css/datetimePicker/datetimePicker5.css') }}
		        <h3 class="box-title">Due :</h3>
		        <?php  ?>
		        <input id="datetimepicker" onchange="saveDate()" value="{{date('Y-m-d H:i:s')}}"/>
		        <input id="DueDate" name="DueDate" type="hidden" value="{{date('Y-m-d H:i:s')}}"/>
		       </div>
		       <!--drive -->
		       <h3 class="box-title">Link :</h3>
		       <div class="" id="Add_Button" onclick="createNew()"><h3 class="box-title">Click Here To Change</h3></div>
		       <input type="hidden" id="resultUrl" name="resultUrl" value="{{ $more->link }}">
		       <div class="box-footer clearfix">
		        <input  class="btn btn-{{ProfileController::get_class()}} pull-right btn  " type="submit" value="Update Assignment">
		        {{ Form::token() }}
		       </div>
			</form>
			@else
			<div class="panel box box-primary" >
			<div class="box-header with-border" >
				<h3 class="box-title"><a target="_blank" href="{{ $more->link }}">View Solutions</a></h3>
				<!--drive --></br></br>
				<div class="" id="Add_Button" onclick="createNew()"><h3 class="box-title">Upload solution</h3></div>
		       <input type="hidden" id="resultUrl" name="resultUrl" value="{{ $more->link }}">
			</div>
			</div>
			@endif
		@else<!--Note : - NonAdmin:Solution Manipulation-->
			@if($TimeRemain>0)
			<h3 class="box-title">Update My solution</h3>
			@else
			<h3 class="box-title">Grade : </h3>
				Grade =
			@endif
		@endif
@endforeach
</div></div>
</br></br>
<div align="right">
<a href="/Assignments/{gId}"><input type=submit class='btn btn-primary' value="Back"></a>
</div>
@endsection
@stop