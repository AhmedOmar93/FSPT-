@extends('layout.mainTheme')

@section('content')
@if($IsAdmin != NULL)
<!--Note : - Insert new-->
<div class="box box-primary box-solid" id="Add_Form">
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
		              document.getElementById('addFormActivated').value = "false";
		        }
		      else {
		            document.getElementById('resultName').value = "Loading File Upload";
		            var my_awesome_script = document.createElement('script');
		            my_awesome_script.setAttribute('src','https://apis.google.com/js/api.js?onload=onApiLoad');
		            document.head.appendChild(my_awesome_script);
		            addForm.style.display = "block";
		            document.getElementById('addFormActivated').value = "true";
		      }
		  }
		</script>

		<div class="box-header">
	     <div class="" id="Add_Button" onclick="createNew()">
	     	<h3 class="box-title">Add Assignment</h3>
	     </div>
	    </div>

	    <div class="box-body" id="Add_Assignment" style="display:none">
	      <form action="{{  URL::route('add-assignment',$gId) }}" method="post" id="">
	       <div class="form-group">
	       	<h3 class="box-title">Title :</h3>
	        <input type='text' class="form-control"  id="resultName" name="resultName"  required="" placeholder="title"/>
	       </div>
	   	   </br>
	   	   <div class="form-group">
	       	<h3 class="box-title">Url :</h3>
	        <input type='text' class="form-control"  id="resultUrl" name="resultUrl"  required="" placeholder="Insert url of file"/>
	       </div>
	   	   </br>
	       <div class="form-group">
	        {{ HTML::script('js/datetimePicker/datetimePicker1.js') }}
	        {{ HTML::style('css/datetimePicker/datetimePicker5.css') }}
	        <h3 class="box-title">Due :</h3>
	        <input id="datetimepicker" onchange="saveDate()"/>
	        <input id="DueDate" name="DueDate" type="hidden">
	       </div>
	    <!--   <input type="hidden" id="resultUrl" name="resultUrl">-->
	       <div class="box-footer clearfix">
	        <input  class="btn btn-{{ProfileController::get_class()}} pull-right btn  " type="submit" value="Add Assignment">
	        {{ Form::token() }}
	       </div>
	      </form>
	    </div>
</div>
@endif
<!--Note : - View Cats-->
<div class="box box-primary box-solid" id="Show_Form">
	    <div class="box-header">
	    	<form action="{{  URL::route('category-assignment') }}" method="post" id="">
	    		<div class="pull-left"><h3 class="box-title">Assignments which are : {{$category}}</h3></div>
	    		<div class="pull-left" style="padding-left: 7%">
	    		<h3 class="box-title">Select :</h3>
			    <select name="SelectBy">
			      <option value=1 >Ongoing</option>
			      <option value=2 >Reached Deadline</option>
			      <option value=3 >Uploaded By Me</option>
			      <option value=4 >Uploaded By Others</option>
			    </select>
			    <input type=submit class='btn btn-primary' value="Go !">
			    </div>
			</form>
	    </div>

	    <div class="box-body" id="show_Assignment">
	    	@if(!$isEmpty)
	    	@foreach($assignmentsList as $assignment)
	    			<div class="panel box box-primary">
	    			<div class="box-header with-border" >
	    			<a  href="{{ url('/Assignment/'.$gId.'/'.$assignment->id) }}"><h3 class="box-title">{{ $assignment->title }}</h3></a>
	    			</div>
	    			</div>
	    	@endforeach
	    	@else
        	<h3 class="box-title">Nothing Here!</h3>
   			@endif
	    </div>
</div>
@endsection
@stop