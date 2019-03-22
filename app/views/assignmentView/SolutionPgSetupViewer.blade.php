@extends('assignment.assignmentlayout')
@section('scripts')
<title>My Assignment Solutions</title>
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
<script type="text/javascript">
 /*|Upload a new solution                    |
    |Triggers the google drive api functionality|
    |Triggers the add form activated         |*/
  function createNew(assignment_id) { 
    var addForm = document.getElementById("Updates");
    document.getElementById('addFormActivated').value = "true";
    document.getElementById('updateFormActivated').value = "false";
    addForm.style.display = "block";
    document.getElementById('newInfoAssignment_id').innerHTML = assignment_id ;
    document.getElementById('newInfoUser_id').value = "hello" 
    document.getElementById('resultName').innerHTML = "Loading File Upload";
    var my_awesome_script = document.createElement('script');
    my_awesome_script.setAttribute('src','https://apis.google.com/js/api.js?onload=onApiLoad');
    document.head.appendChild(my_awesome_script);
    document.getElementById('addFormActivated').value = "true";
  }
 /*|Upload an updated solution file             |
    |Triggers the google drive api functionality|
    |Triggers the update form activated         |*/
  function createAnother(assignment_id){
    var addForm = document.getElementById("Updates");
    document.getElementById('updateFormActivated').value = "true";
    document.getElementById('addFormActivated').value = "false";
    addForm.style.display = "block";
    document.getElementById('newInfoAssignment_id').innerHTML = assignment_id ;
    document.getElementById('newInfoUser_id').value = "Hello" ;
    document.getElementById('resultName').innerHTML = "Loading File Upload";
    var my_awesome_script = document.createElement('script');
    my_awesome_script.setAttribute('src','https://apis.google.com/js/api.js?onload=onApiLoad');
    document.head.appendChild(my_awesome_script);
  }
  </script>
<div id="Solutions">
<div id="SetOfMySolutions">
@if(!$empty)
<div id="Updates" style="display:none">
  <form action="{{  URL::route('setup-solution') }}" method="post" id="">
    <p id='Note'>Please NOTE :you can only change one thing at a time ..</br></p>
    <input type="hidden" id="addFormActivated" name="addFormActivated" value="false"><!--To inform that the next page is add POST-->
    <input type="hidden" id="updateFormActivated" name="updateFormActivated" value="false"><!--To inform that the next page is update POST-->
    <label id="resultName" name="newInfoName"></label>
    <input type="text" id="resultUrl" name="newInfoUrl" ><!--URL-->
    <div id="newInfoUser_id" name="newInfoUser_id" ></div>
    <input type="text" id="newInfoAssignment_id" name="newInfoAssignment_id" >
    <input type="submit" value="Go !">
  </form>
</div>
  @foreach($MySoltions as  $solution)
    <div>
    <a target="_blank" href="{{ $solution->assignment_url }}">{{ $solution->title }}</a>
    <label>{{$solution->due_date}}</label>
    @if(  !empty($solution->solution_url)  )
      <a href="{{ $solution->solution_url }}">View My Solution</a>
      <button onclick='createAnother("{{$solution->id}}")'>Change My File!</button>
      @if(  isset($solution->grade)   )
      <label>Grade : {{$solution->grade}}</label> 
      @else 
      <label>Grade : Waiting ..</label>
      @endif
    @else
      <label>No solution yet !</label>
      <button onclick="createNew()">Upload My Solution!</button>
    @endif 
    </div>
  @endforeach
@else
  <p>Yay \(^^)/ Nothing Here!</p>
@endif
</div>
</div>
@stop