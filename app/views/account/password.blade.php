@extends('layout.mainTheme')

@section('content')
    <form action="{{  URL::route('account-change-password-post') }}" method="post" id="changePassForm">
        <div class="panel">
            Old Password : <input type="password" name="old_password" id="old_password">
            @if($errors->has('old_password'))
                {{ $errors->first('old_password') }}
            @endif
        </div>

        <div class="panel">
            New Password : <input type="password" name="password" id="password">
            @if($errors->has('password'))
                {{ $errors->first('password') }}
            @endif
        </div>

        <div class="panel">
            New Password again: <input type="password" name="password_again" id="password_again">
            @if($errors->has('password_again'))
                {{ $errors->first('password_again') }}
            @endif
        </div>
        <input type="submit" value="change Password">
        {{ Form::token() }}
    </form>
    {{ HTML::script('assests//account//changePassForm.js') }}
@endsection
@stop