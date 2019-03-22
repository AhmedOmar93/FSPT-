<!DOTYPE html>
<html>
<head>
    <title> Social Network</title>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::script('js//bootstrap.min.js') }}
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>

</head>
<body>

@if(Session::has('global'))
    <p>
        {{ Session::get('global') }}
    </p>
@endif

@include('layout.navigation')


@yield('content')
</body>
</html>