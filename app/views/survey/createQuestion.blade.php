@extends('layout.mainTheme')

@section('content')
<?php
$color = ProfileController::get_class();
$surveys = Survey::getAllSurveys();
//var_dump($quizzes);
?>

<div class="box-body">
    <div class="box-group" id="accordion">
        <div class="panel box box-<?php echo $color ?>">

            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="box-body">
                    <table class="table table-bordered text-center">
                        <tr>
                            <td><a class = 'changeColor col-md-6' href="/survey/create"><button class="btn btn-block btn-<?php echo $color ?>">Add survey</button></a>
                                <a class = 'changeColor col-md-6' href="/survey/getAll"><button class="btn btn-block btn-<?php echo $color ?>">All surveys</button></a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

@if($surveys == null)
    <div class="box-header with-border">
        <h3 class="box-title">you must add survey first </h3>
    </div><!-- /.box-header -->
@else


        <!-- general form elements -->
        <div class="box box-<?php echo $color ?>">
            <div class="box-header">
                <h3 class="box-title">Add Question</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form action="{{  URL::route('survey-create-question-post') }}" method="post" id="surveyQ">

                    <div class="form-group">
                        <label>Select survey</label>
                        <select name="surveyTitle" id="surveyTitle" class="form-control">
                            @foreach($surveys as $survey)
                                <option value="{{ $survey->title }}">{{ $survey->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Question : </label>
                        <input type="text" name="question" id="question" class="form-control" placeholder="Enter Question Content">
                    </div>

                    <div class="form-group">
                        <input type="button" class="btn btn-<?php echo $color ?>" id="choice" value="Add Another Answers"/>
                    </div>

                    <div  id="holder">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="choice0" id="choice0" placeholder="choice1" class="form-control">
                            </div><!-- /input-group -->
                        </div>

                        <div class="form-group">
                            <div class="input-group">
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
                        $txt1 = "<div class='form-group'><div class='input-group'>";
                        $txt1 += "<input type='text' class='form-control' placeholder = 'choice "+(count+1)+"' name = 'choice"+count+"' id='choice"+count+"'>";
                        $txt1 += "</div></div>";
                        $("#holder").append($txt1);
                        count++;
                    }
                });
            </script>

            <script>
                jQuery( document ).ready( function() {

                    $('#surveyQ').on( 'submit', function() {
                        $.post(
                                $( this ).prop( 'action' ),
                                $('form#surveyQ').serialize(),
                                function( data ) {
                                    if(data.state == 'success'){
                                        document.getElementById("surveyQ").reset();
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


@endsection
@stop
