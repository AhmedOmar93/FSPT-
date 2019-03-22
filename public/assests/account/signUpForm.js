/**
 * Created by Ahmed on 05/03/2015.
 */



$().ready(function(){
    //validate sign up form
    $("#signUpForm").validate({
        rules:{
            first_name:{
                required: true,
                minlength:3,
                maxlength:20
            },
            middle_name:{
                required: true,
                minlength:3,
                maxlength:20
            },
            last_name:{
                required: true,
                minlength:3,
                maxlength:20
            },
            user_name:{
                required: true,
                minlength:6,
                maxlength:20
            },
            user_code:{
                required: true,
                minlength:4,
                maxlength:20
            },
            email:{
                required:true,
                email:true
            },
            password:{
                required: true,
                minlength:6
            },
            password_again:{
                required: true,
                minlength:3,
                equalTo:"#password"
            }
        },
        messages: {
            first_name: {
                required: "Please enter your first name",
                minlength: "first name must be at least 3 characters",
                maxlength: "first name must be at most 20 characters"
            },
            middle_name: {
                required: "Please enter your middle name",
                minlength: "middle name must be at least 3 characters",
                maxlength: "middle name must be at most 20 characters"
            },
            last_name: {
                required: "Please enter your last name",
                minlength: "last name must be at least 3 characters",
                maxlength: "last name must be at most 20 characters"
            },
            user_name: {
                required: "Please enter your user name",
                minlength: "user name must be at least 6 characters",
                maxlength: "user name must be at most 20 characters"
            },
            user_code: {
                required: "Please enter your user code",
                minlength: "user code must be at least 6 characters",
                maxlength: "user code must be at most 20 characters"
            },
            password: {
                required: "please provide a password ",
                minlength: "password must be at least 6 characters long"
            },
            password_again: {
                required: "please provide a password",
                minlength: "password must be at least 6 characters long",
                equalTo: "please enter the same password as above"
            }
        }
    })
});

$("#user_name").focus(function(){
    var firstName = $("#first_name").val();
    var lastName = $("#last_name").val();
    if(firstName && lastName && ! this.value){
        this.value = firstName + "."+ lastName;
    }
});
