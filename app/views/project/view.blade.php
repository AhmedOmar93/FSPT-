@extends('layout/mainTheme')

@section('header')

{{ HTML::script('module/project.js') }}
{{ HTML::style('css/TSstyle.css') }}  

@stop

@section('content')
<div ng-app="projectDetailsApp">
	<div ng-view></div>
</div>


@stop

