<!DOCTYPE html>
<html>
  <head>
      <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
      <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
      <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <meta charset="UTF-8">
    <title>FCIH | Registration Page</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="../../plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
      <![endif]-->
  </head>
  <body class="register-page" style="background-image:url('../../img/login.jpg');background-size:100% 100%;">
    <div class="register-box" style='margin-top:15px;min-width:300px;'>
      <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>
        <form   action="{{  URL::route('account-create-post') }}" method="post" id="signUpForm" >
         
          <div class="form-group has-feedback">
             <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" {{ (Input::old('first_name'))?'value = "'.e(Input::old('first_name')).'"':'' }} />
            @if($errors->has('first_name'))
                {{ $errors->first('first_name') }}
            @endif
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>


          <div class="form-group has-feedback">
              <input type="text" class="form-control" placeholder="Middle Name"  id="middle_name" name="middle_name" {{ (Input::old('middle_name'))?'value = "'.e(Input::old('middle_name')).'"':'' }}>
            @if($errors->has('middle_name'))
                {{ $errors->first('middle_name') }}
            @endif
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>


          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Last Name"  id="last_name" name="last_name" {{ (Input::old('last_name'))?'value = "'.e(Input::old('last_name')).'"':'' }}>
            @if($errors->has('last_name'))
                {{ $errors->first('last_name') }}
            @endif
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>

            <div class="form-group has-feedback">
              <input type="text" class="form-control" placeholder="Username" id="user_name" name="user_name" {{ (Input::old('user_name'))?'value = "'.e(Input::old('user_name')).'"':'' }}>
              @if($errors->has('user_name'))
                  {{ $errors->first('user_name') }}
              @endif
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Email" id="email" name="email" {{ (Input::old('email'))?'value = "'.e(Input::old('email')).'"':'' }}>
            @if($errors->has('email'))
                {{ $errors->first('email') }}
            @endif
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>

           <div class="form-group has-feedback">
               <input type="text" class="form-control" placeholder="User Code" id="user_code" name="user_code" {{ (Input::old('user_code'))?'value = "'.e(Input::old('user_code')).'"':'' }}>
            @if($errors->has('user_code'))
                {{ $errors->first('user_code') }}
            @endif
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="form-group ">
                @if($errors->has('profession'))
                    {{ $errors->first('profession') }}
                @endif
            <select class='form-control' name="profession" id="profession">
                <option selected="selected">Choose Profession</option>
                <option>doctor</option>
                <option>Student</option>
                <option>employee</option>
            </select>
            </div>

            <div class="form-group ">
                    <label>
                      <input type="radio"  name='gender' value="male" class="minimal" checked/> Male
                    </label>
                    <label>
                      <input type="radio"  name='gender' value="female" class="minimal"/> Female
                    </label>
                    
            </div>

          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Password" id="password"/>
            @if($errors->has('password'))
                {{ $errors->first('password') }}
            @endif
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>

          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password_again" placeholder="Retype password" id="password_again"/>
            @if($errors->has('password_again'))
                {{ $errors->first('password_again') }}
            @endif
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>



          <div class="row">
            <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> I agree to the <a href="#">terms</a>
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
            </div><!-- /.col -->
          </div>
          {{ Form::token() }}
        </form>        
<!--
        <div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign up using Google+</a>
        </div>
      -->

        <a href="{{  URL::route('account-sign-in') }}" class="text-center">I already have a membership</a>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->


    <!-- Bootstrap 3.3.2 JS -->
    <script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>

    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
     {{ HTML::script('assests//account//signUpForm.js') }}
  </body>
</html>