@extends('layout.mainTheme')

@section('content')
<?php
$color = ProfileController::get_class();
?>
<div class="box-body">
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

                </div> <dt>{{ $question->content }}</dt>
                {{--*/ $check = $count /*--}}
                {{--*/ $count++ /*--}}
                @endif
                <div class="input-group">
                    <label>{{ $question->choice }}  {{$question->rate}}</label>
                </div><!-- /input-group -->

                @endforeach
                        </div>


            </div>


<button onclick="jQuery.print('test ahmed')">print</button>


@endsection
@stop
