@extends('layout.mainTheme')

@section('content')
<?php
$color = ProfileController::get_class();
?>

<div class="box-body" xmlns="http://www.w3.org/1999/html">
        <div class="box-group" id="accordion">
            <div class="panel box box-<?php echo $color ?>">

                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <table class="table table-bordered text-center">
                            <tr>
                                <td>
                                    <a class = 'changeColor col-md-6' href="/survey/create/question"><button class="btn btn-block btn-<?php echo $color ?>">Add Quiestion</button></a>
                                    <a class = 'changeColor col-md-6' href="/survey/getAll"><button class="btn btn-block btn-<?php echo $color ?>">All Survey</button></a>
                                </td>

                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>

                <!-- general form elements -->
        <div class="box box-<?php echo $color ?>">
            <div class="box-header">
                <h3 class="box-title">Add Survey</h3>
                <div class="box-body">
                    <dl class="dl-horizontal">
                        <form role="form" action="{{  URL::route('create-survey-post') }}" method="post" id="survey">

                            <div id="surveyTitle">
                                <dt>Title</dt>
                                <dd><input class = 'form-control' type='text' placeholder = 'title' name = 'surveyTitle'/></dd>
                            </div>

                            <div id="aboutSurvey">
                                <dt>About this survey</dt>
                                <dd><input class = 'form-control' type='text' placeholder = 'information about this survey' name = 'about'/></dd>
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
                                <input type="submit" class="btn btn-<?php echo $color ?> pull-right" value="SUBMIT" id="addSurvey">
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

            $('#survey').on( 'submit', function() {
                $.post(
                        $( this ).prop( 'action' ),
                        $('form#survey').serialize(),
                        function( data ) {
                            //alert(data.state)
                            if(data.state == 'success') {
                                $.notify(data.state, "success");
                                document.getElementById("survey").reset();
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
@endsection
@stop
