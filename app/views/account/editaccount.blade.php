@extends('layout.mainTheme')

@section('content')
	<form id="upload_form">
	<div>
        <input type="file" id="uploadimg">
         <input type="submit" value="UPLOAD">
    </div>
    </form>

    <form action="#" method="post" id="editProfileForm">

	<div>
        <label>change First name:</label>
        <input type="text" id="first_name" value="{{Auth::user()->first_name}}">
    </div>
     <div>
        <label>change Middle name:</label>
        <input type="text" id="middle_name" value="{{Auth::user()->middle_name}}">
    </div>
    <div>
       <label>change Last name:</label>
       <input type="text" id="last_name" value="{{Auth::user()->last_name}}">
    </div>
     <div>
        <label>change username:</label>
        <input type="text" id="user_name" value="{{Auth::user()->user_name}}">  
    </div>
    <div>
        <label>change email:</label>
        <input type="email"   id="email" value="{{Auth::user()->email}}">
    </div>
    <div>
    	<label>change gender:</label>
        <select id="gender">
        <option selected>Male</option>
        <option>Female</option>
        </select>
    </div>
    <div>
        <label>change DOB:</label>
        <input type="date"   id="birth_date" value="{{Auth::user()->birth_date}}">
    </div>
    <div>
        <label>change Address:</label>
        <input type="text"  id="street" value="{{Auth::user()->street}}">
    </div>
    <div>
        <label>change city:</label>
        <input type="text"   id="city" value="{{Auth::user()->city}}">
    </div>
    <div>
        <label>change country:</label>
        <input type="text"   id="country" value="{{Auth::user()->country}}">
    </div>
    
    
    <div>
        <label>change phone:</label>
        <input type="text"  id="phone" value="{{Auth::user()->phone}}">
    </div>
    <div>
        <label>change dept:</label>
        <input type="text"  id="dept" value="{{Auth::user()->department}}">
    </div>
    <div>
        <label>change level:</label>
        <select id="level">
        <option selected>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
            </select>
    </div>
    <input type="submit" value="Edit Your Profile">

</form>
{{HTML::script('js/jquery-1.11.2.min.js')}}
<script>
$(document).ready(function() {
    $("#editProfileForm").submit(function(e) {
        e.preventDefault()
		var first_name=$("#first_name").val();
		var middle_name=$("#middle_name").val();
		var last_name=$("#last_name").val();
		var user_name=$("#user_name").val();
		var email=$("#email").val();
		var gender=$("#gender").val();
		var DOB=$("#birth_date").val();
		var street=$("#street").val();
		var city=$("#city").val();
		var country=$("#country").val();
		var phone=$("#phone").val();
		var dept=$("#dept").val();
		var level=$("#level").val();
		$.post('edit-profile-post',{first_name:first_name,middle_name:middle_name,last_name:last_name,
            user_name:user_name,email:email,gender:gender,DOB:DOB,street:street,
            city:city,country:country,phone:phone,dept:dept,level:level},function(data){
			console.log(data.state)
			})
    });
	
	$("#upload_form").submit(function(e) {
		e.preventDefault();
		var avatar=$("#uploadimg");
        $.post('img-upload',{avatar:avatar},function(data){
			
			})
    });
});

</script>
@endsection
@stop