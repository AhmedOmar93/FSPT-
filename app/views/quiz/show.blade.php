<?php
$color = ProfileController::get_class();
?>

@if($questions == null)
    <div class="box-header with-border">
        <h3 class="box-title">add question first .</h3>
    </div><!-- /.box-header -->
    @else

       <div id="result"></div>
    <div class ="box-body" style="">
        @if($checkAdmin)
            <a class = "showAllUserAnswers"  href="/quiz/show/user/answers/{{$quiz_id}}/{{$model}}"><div class="btn btn-<?php echo $color?> pull-right">Show User Answer.</div></a>
        @endif
    </div>
    <div class="box-header" >
        <h3 class="box-title">Answer this Questions </h3>
    </div><!-- /.box-header -->



    <div class="box-body">
        <form action="/quiz/show/{{ $group_id }}/{{$quiz_id}}/{{$model}}" method="post" id="quiz">
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
                @if($checkAdmin)
                <a class = 'do-something' href="/delete/quiz/question/{{ $question->id }}"><div style='margin-left: 10px;' class='btn btn-danger btn-xs'>X</div></a>
                    @endif
            </div> <dt>{{ $question->content }}
                            </dt>



            {{--*/ $check = $count /*--}}
            {{--*/ $count++ /*--}}
        @endif
                            <div class="input-group">
                        <span class="input-group-addon">
                          <input type="radio" name="question{{$count-1}}" value="{{ $question->choice }}"/>
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


<script>
    jQuery( document ).ready( function( $ ) {
        var html = "";
        function drawTableHeader(TableData) {
            html +="<table border=1 style='width:100%;'><tr style='padding:2px;text-align:left;'>";
            for (var index in TableData) {
                html +="<td style='padding:2px;text-align:left;'><b> user name</b></td>";
                html +="<td style='padding:2px;text-align:left;'><b> quiz name</b></td>";
                html +="<td style='padding:2px;text-align:left;'><b> correct</b></td>";
                html +="<td style='padding:2px;text-align:left;'><b> incorrect</b></td>";

            }
            html += "</tr>";
        }

        function drawTableBody(TableData) {
            html +="<tr style='padding:2px;text-align:left;'>";
            for (var index in TableData) {
                html +="<td style='padding:2px;text-align:left;'>"+ TableData[index].user_name + "</td>";
                html +="<td style='padding:2px;text-align:left;'>"+ TableData[index].quiz_name + "</td>";
                html +="<td style='padding:2px;text-align:left;'>"+ TableData[index].correct + "</td>";
                html +="<td style='padding:2px;text-align:left;'>"+ TableData[index].incorrect + "</td>";

            }
            html += "</tr></table>";
        }

        $('#quiz').on( 'submit', function() {
            $.post(
                    $( this ).prop( 'action' ),
                    $('form#quiz').serialize(),
                    function( data ) {
                        alert(data.correct);
                        document.getElementById("quiz").reset();
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

        $('a.showAllUserAnswers').click(function(event){
            $.get($(this).attr('href'),function( data ) {
                        if(data != null){
                            drawTableHeader(data);
                            drawTableBody(data);
                            //console.log(html);
                            jQuery.print(html);
                            html = "";
                        }
                        else{
                            alert('error'); }
                    },
                    'json');
            return false;
        });
    } );

</script>