<?php
        $color = ProfileController::get_class();
       // var_dump(isAdmin);
?>

<?php $quizzes = Quiz::getAllQuizzesByIngroup($gId,2); ?>
<?php $isAdmin = Group::checkMemberInGroupAdmin(Auth::user()->id,$gId) ?>
             @if($isAdmin)
             <div class="box-body">
                 <div class="box-group" id="accordion">
                     <div class="panel box box-<?php echo $color ?>">

                         <div id="collapseOne" class="panel-collapse collapse in">
                             <div class="box-body">
                                 <table class="table table-bordered text-center">
                                     <tr>
                                         <td><a class = 'changeColor col-md-6' href="#/addQuize"><button class="btn btn-block btn-<?php echo $color ?>">Create Quiz</button></a>
                                         <a class = 'changeColor col-md-6' href="#/addQuestionToQuiz"><button class="btn btn-block btn-<?php echo $color ?>">Add Question in exists Quiz</button></a></td>

                                     </tr>

                                 </table>
                             </div>
                         </div>
                 </div>
             </div>

@endif
                 <?php $quizzes = Quiz::getAllQuizzesByIngroup($gId); ?>
                 <?php $isAdmin = Group::checkMemberInGroupAdmin(Auth::user()->id,$gId) ?>
                 @if($quizzes == null)
                     <div class="box-header with-border">
                         <h3 class="box-title">No Quizzes found </h3>
                     </div><!-- /.box-header -->
                     @else
                 <div class="box box-<?php echo $color ?> box-solid">
                     <div class="box-header with-border">
                         <h3 class="box-title">All Quizzes</h3>
                     </div><!-- /.box-header -->

                     @foreach($quizzes as $quiz)
                     <div class="box-body" style='background:#efefef;' id="<?php echo $quiz->id ?>">
                         <div class="box box-<?php echo $color ?>">
                             <div class="box-header with-border">
                                 <h3 class="box-title">
                                     <?php  echo $quiz->name?>
                                     @if($isAdmin)
                                         <a class='deleteQuiz' href="/delete/quiz/<?php echo $quiz->id ?>"><div style='margin-left: 10px;' class='btn btn-danger btn-xs'>X</div></a>
                                     @endif
                                 </h3>
                             </div><!-- /.box-header -->
                             <div class="box-body">
                                 <table class="table table-bordered text-center">
                                     <tr>
                                         <td>
                                             <a class = 'changeColor col-md-4' href="#/quiz/show/<?php echo $gId; ?>/<?php echo $quiz->id ?>/a"><button class="btn btn-block btn-<?php echo $color ?>">A</button></a>
                                             <a class = 'changeColor col-md-4' href="#"></a>
                                             <a class = 'changeColor col-md-4' href="#/quiz/show/<?php echo $gId; ?>/<?php echo $quiz->id ?>/b"><button class="btn btn-block btn-<?php echo $color ?>">B</button></a>
                                         </td>

                                     </tr>
                                     <tr>
                                         <td>
                                             <a class = 'changeColor col-md-4' href="#/quiz/show/<?php echo $gId; ?>/<?php echo $quiz->id ?>/c"><button class="btn btn-block btn-<?php echo $color ?>">C</button></a>
                                             <a class = 'changeColor col-md-4' href="#"></a>
                                             <a class = 'changeColor col-md-4' href="#/quiz/show/<?php echo $gId; ?>/<?php echo $quiz->id ?>/d"><button class="btn btn-block btn-<?php echo $color ?>">D</button></a>

                                         </td>
                                     </tr>
                                 </table>
                             </div><!-- /.box-body -->
                         </div><!-- /.box -->
                         </div>
                         @endforeach

                     </div>
                 </div>


@endif


<script>
    jQuery( document ).ready( function( $ ) {
        $('a.deleteQuiz').click(function (event) {
            $.get($(this).attr('href'), function (data) {
                        //alert(data.state);
                        if(data.state == 'success')
                            $('#'+data.id).remove();
                        else
                            alert('error');
                    },
                    'json');
            return false;
        });
    });
</script>
</div>