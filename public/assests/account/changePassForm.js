/**
 * Created by Ahmed on 05/03/2015.
 */


$().ready(function(){
    //validate sign up form
    $("#changePassForm").validate({
        rules:{
            old_password:{
                required:true,
                minlength:6
            },
            password:{
                required:true,
                minlength:6
            },
            password_again:{
                required:true,
                minlength:6,
                equalTo:"#password"
            }

        },
        messages: {
            password: {
                required: "please provide a password ",
                minlength: "password must be at least 6 characters long"
            },
            password_again: {
                required: "please provide a password",
                minlength: "password must be at least 6 characters long",
                equalTo: "please enter the same password as above"
            },
            old_password:{
                required: "please provide a password ",
                minlength: "password must be at least 6 characters long"
            }
        }
    })


    $("#changeEmailForm").validate({
        rules:{
            old_Email:{
                required:true,
                email:true
            },
            Email:{
                required:true,
                email:true
            },
            Email_again:{
                required:true,
                email:true,
                equalTo:"#Email"
            }

        },
        messages: {
            password: {
                required: "please provide  email ",
                email: "invalid email"
            },
            password_again: {
                required: "please provide email",
                email: "invalid email",
                equalTo: "please enter the same email as above"
            },
            old_password:{
                required: "please provide email ",
                email: "invalid email"
            }
        }
    })

});
