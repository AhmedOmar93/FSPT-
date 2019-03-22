@extends('layout.main')

@section('content')
    <form action="{{  URL::route('account-sign-in-post') }}" method="post" id="signinForm">

        <div class="panel">
            User Code : <input type="text" id="user_code" name="user_code" {{ (Input::old('user_code'))?'value = "'.e(Input::old('user_code')).'"':'' }}>
            @if($errors->has('user_code'))
                {{ $errors->first('user_code') }}
            @endif
        </div>

        <div class="panel">
            Password : <input type="password" name="password" id="password">
            @if($errors->has('password'))
                {{ $errors->first('password') }}
            @endif
        </div>

        <div class="panel">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">
                Remember Me
            </label>

        </div>

        <input type="submit" value="Sign In">
        {{ Form::token() }}
    </form>
    {{ HTML::script('assests//signinForm.js') }}
@endsection
@stop