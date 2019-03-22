<?php
$color = ProfileController::get_class();
$quizzes = Quiz::getAllQuizzes($gId);
        //var_dump($quizzes);
?>

@if($quizzes == null)
    <div class="box-header with-border">
        <h3 class="box-title">you must add quiz first </h3>
    </div><!-- /.box-header -->
    @else
<div class="box-body">
    <div class="box-group" id="accordion">
        <div class="panel box box-<?php echo $color ?>">

            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="box-body">
                    <table class="table table-bordered text-center">
                        <tr>
                            <td><a class = 'changeColor col-md-6' href="#/addQuize"><button class="btn btn-block btn-<?php echo $color ?>">Create Quiz</button></a>
                                <a class = 'changeColor col-md-6' href="#/quizzes"><button class="btn btn-block btn-<?php echo $color ?>">All Quizzes</button></a></td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>

        
        <!-- general form elements -->
        <div class="box box-<?php echo $color ?>">
            <div class="box-header">
                <h3 class="box-title">Add Question</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
    <form action="{{  URL::route('quiz-create-question-post',$gId) }}" method="post" id="quiz">

            <div class="form-group">
                <label>Select Quiz</label>
            <select name="nameQuiz" id="nameQuiz" class="form-control">
                @foreach($quizzes as $quiz)
                    <option value="{{ $quiz->name }}">{{ $quiz->name }}</option>
                @endforeach
            </select>
                </div>

            <div class="form-group">
                <label>Question : </label>
                <input type="text" name="question" id="question" class="form-control" placeholder="Enter Question Content">
            </div>


            <div class="form-group">
                <label>Select MODEL </label>
                 <select name="model" id="model" class="form-control">
                    <option value="a">a</option>
                    <option value="b">b</option>
                    <option value="c">c</option>
                    <option value="d">d</option>
                </select>
        </div>

            <div class="form-group">
            <input type="button" class="btn btn-<?php echo $color ?>" id="choice" value="Add Another Answers"/>
        </div>

        <div  id="holder">
            <div class="form-group">
            <div class="input-group">
                        <span class="input-group-addon">
                          <input type="radio" name="radio" id="radio" value="choice0">
                        </span>
                <input type="text" name="choice0" id="choice0" placeholder="choice1" class="form-control">
            </div><!-- /input-group -->
            </div>

            <div class="form-group">
            <div class="input-group">
                        <span class="input-group-addon">
                          <input type="radio" name="radio" id="radio" value="choice1">
                        </span>
                <input type="text" name="choice1" id="choice1" placeholder="choice2" class="form-control">
            </div><!-- /input-group -->
            </div>
        </div>

        <div class="box-footer">
        <input type="submit" class="btn btn-<?php echo $color ?> pull-right" value="ADD QUESTION" id="addQuestion">
                </div>

    {{ Form::token() }}
    </form>
        </div>
@endif

    <script>
        var count = 2;
        var i = 0;
        var $txt1 = '';
        $("#choice").click(function(){
            if(count >= 5){
                $("#choice").attr("disabled", true);
            }else {
                $txt1 = "<div class='form-group'><div class='input-group'><span class='input-group-addon'>";
                 $txt1 += "<input type='radio' name='radio' value ='choice"+count+"'>";
                 $txt1 += "</span>";
                 $txt1 += "<input type='text' class='form-control' placeholder = 'choice "+(count+1)+"' name = 'choice"+count+"' id='choice"+count+"'>";
                 $txt1 += "</div></div>";
                $("#holder").append($txt1);
                count++;
            }
        });
    </script>

    <script>
        jQuery( document ).ready( function() {

            $('#quiz').on( 'submit', function() {
                $.post(
                        $( this ).prop( 'action' ),
                        $('form#quiz').serialize(),
                        function( data ) {
                          if(data.state == 'success'){
                              document.getElementById("quiz").reset();
                              $.notify('success in add question', "success");
                          }
                          else
                              $.notify('error '+' ! '+data.state, "warm");

                        },
                        'json'
                );
                return false;
            } );

        } );

    </script>

    {{ HTML::script('assests//quiz//create.js') }}
