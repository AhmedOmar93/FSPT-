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
@if(!$valid)
@foreach($assignmentInfo as  $info)
<div class="" id="Upload_New">
  {{ HTML::script('js/SingleAssignmentInfo.js') }}
<div class="" id="Add_Form">
    @if($implemented)
      <p id="implemented">Done !</p>
    @else
    <div class="" id="Add_Button"><button onclick="editInfo()">Edit Assignment</button></div>
    <div class="" id="Add_Assignment" style="display:none">
      <button onclick="changeFile()">Change File</button>
      <form action="{{  url('/api/assignment/setup'.$info->id.'AssignmentUploader') }}" method="post" id="">
      <label>Title: </label>
        <input type="text" id="resultName" name="resultName" value="{{$info->title}}">
      {{ HTML::script('js/datetimePicker/datetimePicker1.js') }}
      {{ HTML::style('css/datetimePicker/datetimePicker5.css') }}
      <label>Due Date : </label>
        <input id="datetimepicker" onchange="saveDate()"/>
      <input id="DueDate" name="DueDate" type="hidden" value="{{$info->due_date}}">
      <input type="hidden" id="resultUrl" name="resultUrl" value="{{$info->url}}">
      <input type="hidden" id="editFormActivated" name="editFormActivated">
      <input type="submit" value="Add ." />
      </form>
    </div>
    @endif
</div>
</br>
<div class ="" id="View_Form">
  <div class ="" id="View_By">
    <form action="{{  url('/api/assignment/setup'.$info->id.'AssignmentUploader') }}" method="post" id="">
    <label>Select Solutions By :</label>
    <select id="SelectBy" name="SelectBy" onchange="showSearch()">
      <option value=1 selected>ID's By Level</option>
      <option value=2 >Alphabetically By Level</option>
      <option value=3 >Level</option>
      <option value=4 >Search By Id</option>
      <option value=5 >Search By Name</option>
    </select>
    <div id="level" style="display:block">
      <label>Level : </label>
      <select id="SelectByLevel" name="SelectByLevel">
        <option value="ALL" selected>ALL</option>
        <option value=1 >1</option>
        <option value=2 >2</option>
        <option value=3 >3</option>
        <option value=4 >4</option>
      </select>
    </div>
    <div id="dept" style="display:block">
      <label>Department : </label>
      <select id="SelectByDept" name="SelectByDept" >
        <option value="All" selected>All</option>
        <option value="CS" >CS</option>
        <option value="IT" >IT</option>
        <option value="IS" >IS</option>
        <option value="SW" >SW</option>
      </select>
    </div>
    <div id="search" style="display:none" >
    <input type="text" id="searchText" name="searchText" value="">
    </div>
    <input type=submit value="Go !">
  </form>
  </div>
  <div class ="" id="View_Assignments">
    <p>Viewed By {{$name}} :</p>
    @if(!$empty)
    @foreach($all as  $solution)
    <div>
      <a target="_blank" href="{{ $solution->url }}">{{ $solution->user_code }}</a>
      <a href="{{ url( ('/api/assignment/setup'.$info->id.'AssignmentUploader'.$solution->user_code) ) }}">View More</a>
    </div>
    @endforeach
    @else
        <p>Nothing Here!</p>
    @endif
  </div>
</div>
 @endforeach
    @else
    <p>There is no such assignment</p>
    @endif
@stop