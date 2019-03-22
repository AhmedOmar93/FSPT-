$().ready(function(){
    //validate create group form
    $("#createGroup").validate({
        rules:{
            name:{
                required: true,
                minlength:3,
                maxlength:30
            },
            group_Syllable:{
                required: true,
                minlength:6,
                maxlength:20
            },
            group_police:{
                required: true,
                minlength:5,
                maxlength:30
            },
            expire_date:{
                required: true
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
            group_Syllable: {
                required: "Please enter group Syllable",
                minlength: "group Syllable must be at least 6 characters",
                maxlength: "group Syllable must be at most 30 characters"
            },
            group_police: {
                required: "Please enter group police",
                minlength: "group police must be at least 5 characters",
                maxlength: "group police must be at most 30 characters"
            },
            expire_date: {
                required: "Please enter group expire date"

            },
            image: {
                accept: "image must be JPG, GIF or PNG",
                filesize: "image size must be less than or equal 1MB"
            }
            }

    })
});


