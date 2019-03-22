/**
 * Created by Ahmed on 16/04/2015.
 */


$().ready(function(){
    //validate create group form
    $("#quiz").validate({
        rules:{
            question:{
                required: true,
                minlength:10
            },
            radio:{
                required: true
            },
            nameQuiz:{
                required: true
            }
        },
        messages: {
            question: {
                required: "Please enter question",
                minlength: "question must be at least 10 characters"
            },
            radio: {
                required: "Please select the correct choice"
            },
            nameQuiz: {
                required: "Please go to add quiz first"
            }
        }

    })
});

