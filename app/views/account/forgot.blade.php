
    <!DOCTYPE html>
    <html>
    <head>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
        <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
        <meta charset="UTF-8">
        <title>AdminLTE 2 | Log in</title>
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

    <body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>Admin</b>LTE</a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Recover your password by email</p>

            <form action="{{  URL::route('account-forgot-password-post') }}" method="post" id="forgetForm">
        <div class="panel">
            <input type="text" id="email" name="email" class="form-control" placeholder="email" {{ (Input::old('email'))?'value = "'.e(Input::old('email')).'"':'' }}>
            @if($errors->has('email'))
                {{ $errors->first('email') }}
            @endif
        </div>
        <input type="submit" value="Recover" class="btn btn-primary btn-block btn-flat">
        {{ Form::token() }}
    </form>
            </div>
        </div>

    {{ HTML::script('assests//account//forgetPasswordForm.js') }}
    </body>
    </html>