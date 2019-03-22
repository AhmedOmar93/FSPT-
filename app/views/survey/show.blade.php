@extends('layout.mainTheme')

@section('content')
<?php
$color = ProfileController::get_class();
?>
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
@if($check)
    <div class="box-header with-border">
        <h3 class="box-title">thanks you fill this survey before .</h3>
    </div><!-- /.box-header -->
    @else
@if($questions == null)
    <div class="box-header with-border">
        <h3 class="box-title">add question first .</h3>
    </div><!-- /.box-header -->
@else

    <div class="box-header" >
        <h3 class="box-title">Answer this Questions </h3>
    </div><!-- /.box-header -->



    <div class="box-body">
        <form action="{{  URL::route('check-answer-post',$survey_id) }}" method="post" id="surveyAnswer">
            {{--*/ $cont = '' /*--}}
            {{--*/ $count = 1 /*--}}
            @foreach($questions as $question)

                @if($question->content != $cont)
                    @if($count == 1)
                        </br><div id="{{ $question->id }}">
                            @else
                        </div>
                        </br><div id="{{ $question->id }}">
                    @endif

                            {{--*/ $cont = $question->content /*--}}


                            <div class="dl-horizontal">Question ({{$count}}) is :

                                    <a class = 'do-something' href="/delete/survey/question/{{ $question->id }}"><div style='margin-left: 10px;' class='btn btn-danger btn-xs'>X</div></a>

                            </div> <dt>{{ $question->content }}
                            </dt>
                            {{--*/ $check = $count /*--}}
                            {{--*/ $count++ /*--}}
                            @endif
                            <div class="input-group">
                        <span class="input-group-addon">
                          <input type="radio" name="question{{$count-1}}" value="{{ $question->cid }}"/>
                        </span>
                                <label class="form-control">{{ $question->choice }}</label>
                            </div><!-- /input-group -->

                            @endforeach
                        </div>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-<?php echo $color ?> pull-right" value="submit" id="addQuestion">
                        </div>
                        {{ Form::token() }}
        </form>
    </div>


@endif
@endif


<script>
    jQuery( document ).ready( function( $ ) {

        $('#surveyAnswer').on( 'submit', function() {
            $.post(
                    $( this ).prop( 'action' ),
                    $('form#surveyAnswer').serialize(),
                    function( data ) {
                        alert(data.state);
                        document.getElementById("surveyAnswer").reset();
                    },
                    'json'
            );
            return false;
        } );


        $('a.do-something').click(function(event){
            $.get($(this).attr('href'),function( data ) {
                        if(data.success == 'success')
                            $('#'+data.id).remove();
                        else
                            alert('error');
                    },
                    'json');
            return false;
        });
    } );

</script>
@endsection
@stop
