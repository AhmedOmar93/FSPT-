@extends('layout.mainTheme')

@section('content')
<?php
        $color = ProfileController::get_class();
       // var_dump(isAdmin);
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

                 @if($surveys == null)
                     <div class="box-header with-border">
                         <h3 class="box-title">No Surveys Found </h3>
                     </div><!-- /.box-header -->
                     @else
                 <div class="box box-<?php echo $color ?> box-solid">
                     <div class="box-header with-border">
                         <h3 class="box-title">All Quizzes</h3>
                     </div><!-- /.box-header -->

                     @foreach($surveys as $survey)
                         <div class="box-body" style='background:#efefef;' id="<?php echo $survey->id ?>">
                             <div class="box box-<?php echo $color ?>">
                                 <div class="box-header with-border">
                                     <h3 class="box-title">
                                         <a href="/survey/showData/<?php echo $survey->id ?>">Result  </a>
                                         <a href="/survey/show/<?php echo $survey->id ?>"><?php  echo $survey->title?></a>
                                         <a class='deleteSurvey' href="/delete/survey/<?php echo $survey->id ?>"><div style='margin-left: 10px;' class='btn btn-danger btn-xs'>X</div></a>
                                     </h3>
                                 </div><!-- /.box-header -->
                                 <div class="box-body">
                                     <table class="table text-left">
                                         <tr>
                                             <td>
                                                 <?php echo $survey->about?>
                                             </td>

                                         </tr>
                                         <tr>
                                             <td>
                                                 End date : <?php echo $survey->end_at?>
                                             </td>
                                         </tr>
                                     </table>
                                 </div><!-- /.box-body -->
                             </div><!-- /.box -->
                         </div>

                     @endforeach
                 </div>

@endif


<script>
    jQuery( document ).ready( function( $ ) {
        $('a.deleteSurvey').click(function (event) {
            $.get($(this).attr('href'), function (data) {
                        alert(data.state);
                            $('#'+data.id).remove();
                    },
                    'json');
            return false;
        });
    });
</script>
</div>
@endsection
@stop
