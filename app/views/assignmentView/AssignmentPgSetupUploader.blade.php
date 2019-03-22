@extends('assignment.assignmentlayout')

@section('scripts')
<title>Assignment</title>
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
@stop

@section('content')
<div class="" id="Upload_New">
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
<div class="" id="Add_Form">
    @if($implemented)
      <p id="implemented">Done !</p>
    @else
    <div class="" id="Add_Button"><button onclick="createNew()">Add new assignment</button></div>
    <div class="" id="Add_Assignment" style="display:none">
      <form action="{{  URL::route('setup-assignment') }}" method="post" id="">
      <label for="Title">Title: </label>
        <input type="text" id="resultName" name="resultName">
      {{ HTML::script('js/datetimePicker/datetimePicker1.js') }}
      {{ HTML::style('css/datetimePicker/datetimePicker5.css') }}
      <label for="DueDate">Due Date : </label>
        <input id="datetimepicker" onchange="saveDate()"/>
      <input id="DueDate" name="DueDate" type="hidden">
      <input type="hidden" id="resultUrl" name="resultUrl">
      <input type="hidden" id="addFormActivated" name="addFormActivated">
      <input type="submit" value="Add ." />
      </form>
    </div>
    @endif
</div>
</br>
<div class ="" id="View_Form">
  <div class ="" id="View_By">
    <form action="{{  URL::route('setup-assignment') }}" method="post" id="">
    <p>Get assignments that :</p>
    <select name="SelectBy">
      <option value=1 >Still Ongoing</option>
      <option value=2 >Reached Deadline</option>
      <option value=3 >Uploaded By Me</option>
      <option value=4 >Uploaded By Others</option>
    </select>
    <input type=submit value="Go !">
  </form>
  </div>
  <div class ="" id="View_Assignments">
    <p>Viewed By {{$name}} :</p>
    @if(!$empty)
    @foreach($all as  $assignment)
    <div>
      <div><a target="_blank" href="{{ $assignment->url }}">{{ $assignment->title }}</a></div>
      <div><a href="{{ url('/api/assignment/setup'.$assignment->id.'AssignmentUploader') }}">View More</a></div>
    </div> 
    @endforeach
    @else
        <p>Nothing Here!</p>
    @endif
  </div>
</div>
@stop