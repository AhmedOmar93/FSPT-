//technical_support Services 
var full_name = User.first_name + " " + User.last_name;
var answer_number = 0;
var questions_loaded = false;

myApp.factory('technical_support', function ($http, Data) {

    return{
        add_answer: function (answer) {
            return $http.post(Data.path_prefix + '/api/answer/add', answer);

        },
        add_comment: function (comm, id) {
            $http.post(Data.path_prefix + '/api/comment/add', {'content': comm, 'answer_id': id})
                    .success(function (data) {
                        if (data != "fail") {

                            $("#comment_container" + id).append("<table style='width:100%'><tr><td valign='top' style='padding:0px;width:60px' rowspan='2'><img class='pp' src='" + Data.image_path + "/" + User.pic + "'/></td><td valign='top' style='padding:0px;height:10px;'>" + full_name + " <div class='btn btn-danger btn-xs pull-right' onclick='delete_new_com(" + data + ",event)'>X</div></td></tr><tr style='padding:0px;background:transparent;'><td valign='top' >" + comm + "</td></tr></table>");

                            document.getElementById("comment" + id).value = "";
                        }
                    })
                    .error(function (data) {
                        alert(data);
                    });
        },
        get_question_answs: function (q_id) {
            return $http.get(Data.path_prefix + '/api/answer/get_all/' + q_id);
        },
        get_group_questions: function () {
            return $http.get(Data.path_prefix + 'api/question/get_all/' + group.id);
        },
        add_question: function (question) {
            return $http.post(Data.path_prefix + '/api/add/question', question);
        },
        delete_ques: function (id) {
            return $http.get(Data.path_prefix + 'api/question/delete/' + id);
        },
        delete_ans: function (id) {
            return $http.get(Data.path_prefix + 'api/answer/delete/' + id);
        },
        delete_com: function (id) {
            return $http.get(Data.path_prefix + 'api/comment/delete/' + id);
        },
        answer_rate: function (answer) {
            return $http.post(Data.path_prefix + 'api/answer/rate', answer);
        },
        get_question: function (id) {
            return $http.get(Data.path_prefix + 'api/question/get/' + id);
        },
        get_more_questions: function (qId) {
            return $http.get(Data.path_prefix + "/api/question/get_prev/" + group.id + "/" + qId);
        }, mark_right_ans: function (answerId) {
            return $http.get(Data.path_prefix + "/api/answer/markRight/" + answerId);
        }

    }

});

var wait = false;
myApp.controller('questionListController', function ($scope, $http, $filter, technical_support, Data) {

    $(document).ready(function () {
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
                if (!questions_loaded && !wait) {
                    wait = true;
                    load_questions();
                }
            }
        });

    });

    active_tape(2);

    $scope.className = className;
    //$scope.color=
    $scope.path = Data;
    $scope.userId = User.id;
    $scope.letter_number = 5;

    function load_questions() {

        var qId = all_Question[all_Question.length - 1].id;
        technical_support.get_more_questions(qId).success(function (data) {
            if (data.length == 0) {
                $("#get_more_questions").hide();
                $("#get_more_questions").after("<p>There is No More Questions</p>");
                questions_loaded = true;
            } else {
                for (var i = 0; i < data.length; i++) {
                    all_Question.push(data[i]);
                }
            }
            wait = false;
        }).error(function (data) {
            console.log(data);
        });
    }

    $("#get_more_questions").click(function () {
        load_questions();
    });

    //get all question insiede group as json array
    if (!load_question) { // if list is empty
        technical_support.get_group_questions().success(function (data) {
            all_Question = data;
            $scope.artists = data;
            load_question = true;

        }).error();

    } else {
        $scope.artists = all_Question;
    }

    // $(document).scrollTop(questionPosition);

    $scope.goTo = function (question) {
        // questionPosition=$(document).scrollTop();
        //alert(questionPosition);
        window.location.href = "#/questionDetails/" + question.id;

    }

    $scope.delete_ques = function (ques, event) {
        technical_support.delete_ques(ques.id)
                .success(function (data) {


                    var element = $(event.target).closest('li');
                    element.fadeOut(400, function () {
                        element.remove();
                        var index = $scope.artists.indexOf(ques);
                        all_Question.splice(index, 1);

                    });

                });
    }

}
);


myApp.controller('questionDetailsController', function ($scope, $http, $routeParams, technical_support, Data) {

    $scope.answers_loaded = "<img src='" + Data.path_prefix + "img/loading1.gif' class='kok' />";
    active_tape(2);
    $(document).scrollTop(0);
    $scope.answers;
    $scope.className = className;
    $scope.color = color;
    $scope.colorName = colorName;

    $scope.path = Data;
    $scope.userId = User.id;

    $("#quetions").addClass('active');


    $scope.add_answer = function (answer, form, Q) {
        var new_answer = {};

        if ($(".a_content").val() != "") {
            new_answer.content = $(".a_content").val();
            new_answer.question_id = Q.id;
            technical_support.add_answer(new_answer).success(function (data) {
                data.answer_time = "Just Now";
                data.owner = {"id": User.id, "profile_picture": User.pic, "first_name": User.first_name, "last_name": User.last_name};
                $scope.answers.push(data);
                $('.a_content').data("wysihtml5").editor.clear();
                //$("#answers li:eq("+position+")").before("<li><img class='pp' src='"+Data.image_path+"/"+User.pic+"'/><div class='timeline-item' style='margin-top:-60px;'><div id='answers_content' class='box box-solid'><div  class='box-body'><div class='box-group' id='accordion'><div  class='panel box box-primary answer post_cont'><div class='box-header bg-primary with-border'><div class='arrow solid pull-left'></div><h3 class='box-title'>"+full_name+" answer</h3></div><div id='collapseOne' class='panel-collapse collapse in'><div class='box-body'><blockquote><p><div class='qContent'>"+answer.content+"</div></p><small>Just Now</small></blockquote></div></div></div></div></div></div></div></div></li>");
                answer_number++;
                return true;
            }).error(function (data) {
                console.log(data);
            });

        }
    }


    $scope.delete_ans = function (answer, event) {
        technical_support.delete_ans(answer.id)
                .success(function (data) {
                    if (data.length != 4) {
                        var element = $(event.target).closest('li');
                        element.fadeOut(400, function () {
                            element.remove();
                            answer_number--;
                            //  alert("sucessfully Deleted"); 
                        });
                    } else {
                        alert("Can't delete Answer,Reload Page and Retry");
                    }
                });
    }

    $scope.mark_right_ans = function (answer, event) {
        technical_support.mark_right_ans(answer.id)
                .success(function (data) {
                    if (data.length != 4) {
                        alert("right answer");
                        answer.is_right = true;
                    } else {
                        alert("Can't mark right Answer,Reload Page and Retry");
                    }
                    console.log(data);
                });
    }

    $scope.delete_com = function (comment, event) {
        technical_support.delete_com(comment.id)
                .success(function (data) {
                    if (data.length != 4) {
                        var element = $(event.target).closest('table');
                        element.fadeOut(400, function () {
                            element.remove();
                            //  alert("sucessfully Deleted"); 
                        });
                    } else {
                        alert("Can't delete comment Now ,Reload Page and Retry");
                    }
                });
    }



    $scope.add_comment = function (answer, event) {

        if (event.keyCode == 13) {
            var comm = document.getElementById("comment" + answer.id).value;
            if (comm.length != 0) {
                technical_support.add_comment(comm, answer.id);
            } else {
                alert("empty comments n't allowed!");
            }
        }

    }

    $scope.answer_rate = function (answer, value) {

        answer.checked = true;

        if (value == 1) {
            answer.rate++;
        } else if (value == 0) {
            answer.rate--;
        }
        answer.target = value;

        technical_support.answer_rate(answer).success(function (data) {
            // alert(data);
        }).error(function (data) {
            // $scope.result=data;
            alert("Fail Rating Now , Try Again Later");
        });

    }

//get answers with comments on it

    $scope.questionOwner = false;
    technical_support.get_question($routeParams.q_id).success(function (ques) {

        $scope.question = ques;
        if ($scope.question.owner.id == User.id) {
            $scope.questionOwner = true;
        }
        if (ques.length == 0) {
            alert("This Questions Has Been Deleted");
        }

        technical_support.get_question_answs(ques.id).success(function (answers) {

            $scope.answers = answers;
            answer_number = answers.length;
            $scope.answers_loaded = "";

        }).error(function (data) {

            alert("Can't load Answers", 'error');
            console.log(data);
        });

    }).error();



});

myApp.controller('notFoundController', function ($scope) {

    $scope.myMessages = {notFound: "This Page Not Found!", mess2: 'ay 7aga'};

});


myApp.filter('tags', function () {
    return function (text) {
        var tags = "<div class='tag bg-" + colorName + "'>" + text.split(",").join("</div><div class='tag bg-" + colorName + "'>") + "</div>";
        return tags;
    }
});

myApp.controller('addQuestion', function ($scope, $http, technical_support) {

    active_tape(2);
    $scope.showResult = 'none';
    $scope.result = "";
    $scope.myClass = className;

    $scope.add_question = function (ques, form) {

        $scope.showResult = "block";
        if (!form.$error.required && $(".q_content").val() != "") {

            $scope.ques.group_id = group.id;
            $scope.ques.content = $(".q_content").val();
            console.log($scope.ques);
            $scope.myData = angular.toJson($scope.ques);
            technical_support.add_question($scope.myData).
                    success(function (data, status, headers, config) {
                        if (data.length != 4) {//which mean no error

                            data.owner = {"id": User.id, "profile_picture": User.pic, "first_name": User.first_name, "last_name": User.last_name};
                            data.question_time = "Just Now";
                            all_Question.unshift(data);
                            // alert(all_Question);
                            window.location = "#/questions"; //redirect to all questions list
                        }
                    })
                    .error(function (data, status, headers, config) {
                        $scope.result = data;
                        //alert(data);
                    });
            //$scope.result=x;


        } else {
            $scope.result = "Check Valid Data";
        }
    }
});

myApp.controller('userQuestionController', function () {
});

function delete_new_com(id, event) {

    var target_url = path_prefix + 'api/comment/delete/' + id;

    $.ajax({url: target_url, success: function (data) {
            if (data.length != 4) {
                var element = $(event.target).closest('table');
                element.fadeOut(400, function () {
                    element.remove();
                    //  alert("sucessfully Deleted"); 
                });
            } else {
                alert("Can't delete comment Now ,Reload Page and Retry");
            }
        }
    });

}

