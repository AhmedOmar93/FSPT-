<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Social Network</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.2 -->

        {{ HTML::style('js/plugins/introjs/introjs.css') }}

        {{ HTML::style('bootstrap/css/bootstrap.min.css') }}


        <!-- Font Awesome Icons -->
        <link rel="stylesheet" type="text/css" href="{{URL::asset('dist/css/font-awesome-4.3.0/css/font-awesome.min.css')}}">
        <!--<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />-->
        <!-- Ionicons -->
        <link rel="stylesheet" type="text/css" href="{{URL::asset('dist/css/Ionicons-2.0.min.css')}}">
        <!--<link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />-->
        <!-- Theme style -->

        {{ HTML::style('dist/css/AdminLTE.min.css') }}
        {{ HTML::style('dist/css/skins/skin-blue.min.css') }}
        {{ HTML::style('dist/css/skins/_all-skins.min.css') }}

        {{HTML::style('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}
        {{HTML::style('css//normalize.min.css')}}

        {{HTML::script("plugins/jQuery/jQuery-2.1.3.min.js")}}

        {{HTML::script("js/jquery_lib.js")}}

        {{HTML::script("js/notifyjs.js")}}


        {{HTML::script('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}




        <!-- add by adel   -->

        {{HTML::script("http://jqueryvalidation.org/files/dist/jquery.validate.min.js")}}
        {{HTML::script("http://jqueryvalidation.org/files/dist/additional-methods.min.js")}}



        <!-- REQUIRED JS SCRIPTS -->


        <script type="text/javascript" src="{{URL::asset('js/angular.min.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('js/angular-route.min.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('js/angular-animate.min.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('js/angular-sanitize.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('js/ng-device-detector.min.js')}}"></script>





        <!-- jQuery 2.1.3 -->


        <!-- Optionally, you can add Slimscroll and FastClick plugins. 
              Both of these plugins are recommended to enhance the 
              user experience -->





        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript" src="{{URL::asset('js/brain-socket.min.js')}}" ></script>
        <script type="text/javascript"  src="{{URL::asset('js/date.format.js')}}"></script> 
        <script type="text/javascript" src="{{URL::asset('js/Queue.js')}}"></script>

        <script type="text/javascript">
        var User = {
        pic:"{{Auth::user()->profile_picture}}",
                first_name:"{{Auth::user()->first_name}}",
                last_name:"{{Auth::user()->last_name}}",
                color:"{{Auth::user()->color}}"
        }



//to make id non editable id 
Object.defineProperty(User, "id", {
value: {{Auth::id()}},
        writable: false,
        enumerable: true,
        configurable: true
});        </script>


        <!-- globale js file -->
        <script type="text/javascript" src="{{URL::asset('js/global.js')}}"></script>
        {{HTML::script("plugins/slimScroll/jquery.slimscroll.js")}}
        {{HTML::script("js/plugins/introjs/intro.js")}}

        {{HTML::style('css/global.css')}}

        @yield('header')

    </head>
