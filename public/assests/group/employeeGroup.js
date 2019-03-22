$().ready(function(){
    //validate create group form
    $("#createTGroup").validate({
        rules:{
            name:{
                required: true,
                minlength:3,
                maxlength:30
            },
            group_des:{
                required: true,
                minlength:3,
                maxlength:500
            },
            image: {
                accept: "png|jpe?g|gif",
                filesize: 1048576
            }
        },
        messages: {
            name: {
                required: "Please enter group name",
                minlength: "group name must be at least 3 characters",
                maxlength: "group name must be at most 30 characters"
            },
            group_des: {
                required: "Please enter group description",
                minlength: "group description must be at least 3 characters",
                maxlength: "group description  must be at most 500 characters"
            },
            image: {
                accept: "image must be JPG, GIF or PNG",
                filesize: "image size must be less than or equal 1MB"
            }
        }

    })
});


