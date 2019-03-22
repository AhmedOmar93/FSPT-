/**
 * Created by Ahmed on 05/03/2015.
 */


$().ready(function(){
    //validate sign up form
    $("#signinForm").validate({
        rules:{
            user_code:{
                required:true,
                minlength:6
            },
            password:{
                required:true,
                minlength:6
            }

        },
        messages: {
            password: {
                required: "please provide a password ",
                minlength: "password must be at least 6 characters long"
            },
            user_code : {
                required: "please enter user code",
                minlength: "password must be at least 6 characters long"
            }
        }
    })
});
