/**
 * Created by Ahmed on 05/03/2015.
 */

$().ready(function(){
    //validate sign up form
    $("#forgetForm").validate({
        rules:{
            email:{
                required:true,
                email:true
            }
        }
    })
});
