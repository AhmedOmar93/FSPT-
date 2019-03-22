<!DOCTYPE html>
<html>
  <head>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <meta charset="UTF-8">
    <title>FCIH
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="../plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>

    <![endif]-->
    <style>
        
        video#bgvid {
position: fixed;
top: 50%;
left: 50%;
min-width: 100%;
min-height: 100%;
width: auto;
height: auto;
z-index: -100;
-webkit-transform: translateX(-50%) translateY(-50%);
transform: translateX(-50%) translateY(-50%);
background-size: cover;
}
    </style>
  </head>
  <body class="login-page" style="background-image:url('../../img/login.png');background-size:100% 100%;">
    <!--<video  autoplay  src='../bg.mp4' id="bgvid" loop>
        <source src="../bg.mp4" type="video/mp4">
    </video>-->
    <div class="login-box" >
      <div class="login-logo">
      </div><!-- /.login-logo -->
      <div class="login-box-body" style='background:rgba(255,255,255,0.7);padding:40px;'>
          @if(Session::has('global'))
          <p>
              {{ Session::get('global') }}
          </p>
          @endif
        <form action="{{  URL::route('account-sign-in-post') }}" method="post" id="signinForm">
          <div class="form-group has-feedback">
             <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            <input type="text" class="form-control" placeholder="Usercode" id="user_code" name="user_code" {{ (Input::old('user_code'))?'value = "'.e(Input::old('user_code')).'"':'' }}>
          </div>
          <div class="form-group has-feedback">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password"/>
          </div>
          <div class="row">
            <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember" id="remember"> Remember Me
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
              {{ Form::token() }}
            </div><!-- /.col -->
          </div>
        </form>

        <a href="{{  URL::route('account-forgot-password') }}">I forgot my password</a><br>
        <a href="{{  URL::route('account-create') }}" class="text-center">Register a new membership</a>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- Bootstrap 3.3.2 JS -->
    <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
    {{ HTML::script('assests//account//signinForm.js') }}
  </body>
</html>