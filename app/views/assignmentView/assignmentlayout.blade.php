<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    @yield('scripts')
  </head>
  <body>
  	@section('sidebar')
            This is the master sidebar.
        @show
  	<div>@yield('content')</div>
  </body>
</html>