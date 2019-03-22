@extends('layout/mainTheme')

@section('header')

    <script type="text/javascript" src='lib/angular/angular.min.js'></script>
    <script type="text/javascript" src='lib/angular/angular-route.min.js'></script>
    <script type="text/javascript" src='lib/angular/angular-animate.min.js'></script>
    <script type="text/javascript" src='lib/angular/angular-sanitize.js'></script>
    
<script>
	var userId={{Auth::id()}}
</script>

<script type="text/javascript" src='module/questionControllers.js'></script>
<link rel="stylesheet" type="text/css" href="css/foundation.min.css">
<link rel="stylesheet" type="text/css" href="css/TSstyle.css">
@stop

@section('content')
<div ng-app="myApp">
<div class="main" style='border:0px solid red;width:700px;margin:0px auto;' ng-view></div>
</div>
@stop