<?php
$color = ProfileController::get_class();
?>
<?php $isAdmin = Group::checkMemberInGroupAdmin(Auth::user()->id,$gId) ?>
@if($isAdmin )
<div class="box-body">
    <div class="box-group" id="accordion">
        <div class="panel box box-<?php echo $color ?>">

            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="box-body">
                    <table class="table table-bordered text-center">
                        <tr>
                            <td><a class = 'changeColor col-md-6' href="#/addQuestionToQuiz"><button class="btn btn-block btn-<?php echo $color ?>">Add Quiestion to Quiz</button></a>
                                <a class = 'changeColor col-md-6' href="#/quizzes"><button class="btn btn-block btn-<?php echo $color ?>">All Quizzes</button></a></td>

                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
        <!-- general form elements -->
        <div class="box box-<?php echo $color ?>">
            <div class="box-header">
                <h3 class="box-title">Add Quiz</h3>
                <div class="box-body">
                    <dl class="dl-horizontal">
                        <form role="form" action="{{  URL::route('quiz-create-quiz-post',$gId) }}" method="post" id="quiz55">

                            <div id="QuizName">
                                <dt>Quiz Name</dt>
                                <dd><input class = 'form-control' type='text' placeholder = 'name' name = 'quizName'/></dd>
                            </div>

                            <div id="StartDate">
                                <dt>Start Date</dt>
                                <dd><input type='date' class='form-control' name='start_date'/></dd>
                            </div>
                            <div id="EndDate">
                                <dt>End Date</dt>
                                <dd><input type='date' class='form-control' name='end_date'/></dd>
                            </div>
                            <div class="box-footer">
                                <input type="submit" class="btn btn-<?php echo $color ?> pull-right" value="SUBMIT" id="addQuiz">
                            </div>
                            {{ Form::token() }}
                        </form>
                    </dl>
                </div>

            </div><!-- /.box-header -->
    </div><!-- /.box -->


</div>

    <script>
        jQuery( document ).ready( function() {

            $('#quiz55').on( 'submit', function() {
                $.post(
                        $( this ).prop( 'action' ),
                        $('form#quiz55').serialize(),
                        function( data ) {
                            //alert(data.state)
                            if(data.state == 'success') {
                                $.notify(data.state, "success");
                                document.getElementById("quiz55").reset();
                            }else{
                                $.notify(data.state, "warm");
                            }
                        },
                        'json'
                );
                return false;
            } );
        } );
    </script>