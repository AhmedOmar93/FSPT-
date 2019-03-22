<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 02/04/2015
 * Time: 10:20 م
 */

class QuizController extends BaseController{

    public function getCreateQuestion($gId){
        $quizzes = Quiz::getAllQuizzes(Auth::user()->id,$gId);
        return View::make('quiz.create')
            ->with('quizzes',$quizzes)
            ->with('gId',$gId);
       // return var_dump($quizzes);
    }
    public function postCreateQuestion($id){

        $count = 0;
      //  $quiz_id = Quiz::getQuizId(Input::get('nameQuiz'),$gId);
       // echo $gId;
       // var_dump($quiz_id);
        //die();
        $response =array();
        foreach(Input::all() as $val ){
            $count++;
        }
        $count = $count - 5;
        $data = array(
            'content'=>Input::get('question'),
            'model'=>Input::get('model'),
            'created_at'=>Carbon\Carbon::now()->toDateTimeString(),
            'updated_at'=>Carbon\Carbon::now()->toDateTimeString(),
            'quiz_id'=>Quiz::getQuizId(Input::get('nameQuiz'),$id)->id,
            'updated_at'=>Carbon\Carbon::now()->toDateTimeString()
        );

        if(Input::has('choice0')){
            if(Input::has('radio')) {
                $id = Quiz::addQuestion($data);
                for ($i = 0; $i < $count; $i++) {
                    if ('choice' . $i == Input::get('radio')) {
                        $choice = array(
                            'content' => Input::get('choice' . $i),
                            'question_id' => $id,
                            'answer' => 'true'
                        );
                    } else {
                        $choice = array(
                            'content' => Input::get('choice' . $i),
                            'question_id' => $id,
                            'answer' => 'false'
                        );
                    }
                    Quiz::createChoice($choice);
                }
                $response = array(
                    'state' => 'success'
                );
            }else{
                $response = array(
                    'state' => 'select the correct answer.'
                );
            }
        }else {
            $response = array(
                'state' => 'no choice enter'
            );
        }
        return Response::json($response);
      // return Response::json(Input::all());
    }

    public function getCreateQuiz($gId){
        //$subjects = Quiz::getAllSubject();
        $groups = Group::getAllGroupsByUserId(Auth::user()->id);
        return View::make('quiz.createQuiz')
            ->with('groups',$groups)
            ->with('gId',$gId);
    }

    public function postCreateQuiz($gId){
        $response = array();
        if(Input::get('quizName') == null){
            $response = array(
                'state' => 'the name of quiz required !'
            );
        }else {
            if (Input::get('start_date') == null || Input::get('end_date') == null) {
                $response = array(
                    'state' => 'start date and end date required!'
                );
            } else {
                $quiz = array(

                    'name' => Input::get('quizName'),
                    'start_date' => Input::get('start_date'),
                    'end_date' => Input::get('end_date'),
                    'group_id' => $gId,
                    'user_id' => Auth::user()->id
                );
                Quiz::createQuiz($quiz);
                $response = array(
                    'state' => 'success'
                );
            }
        }
         return Response::json($response);
    }

    public function getSubjectQuestions($group_id,$quiz_id,$model){
        $data = Quiz::getQuizQuestionsWithChoices($group_id,$quiz_id,$model);
        $checkAdmin = Group::checkMemberInGroupAdmin(Auth::user()->id,$group_id);
        return View::make('quiz.show')
             ->with('questions',$data)
            ->with('group_id',$group_id)
            ->with('quiz_id',$quiz_id)
            ->with('checkAdmin',$checkAdmin)
            ->with('model',$model);
    }

    public function postQuizAnswer($group_id,$quiz_id,$model){

       // return Input::get('question'.$i);
        //return Input::all();

        $data = Quiz::getQuizQuestionsWithCorrectChoices($group_id,$quiz_id,$model);
        $count = 0;
        foreach($data as $val ){
            $count++;
        }
        //return $count;

        $correct = 0;
        for($i=1;$i<=$count;$i++){
            if(Input::get('question'.$i) == $data[$i-1]->choice){
                $correct++;
            }
        }

        $data = array(
            'user_id'=>Auth::user()->id,
            'module'=>$model,
            'quiz_id'=>$quiz_id,
            'correct'=>$correct,
            'incorrect'=>$count-$correct
        );

         Quiz::addUserAnswer($data);
        $response=array(
            'correct' =>$correct,
            'inCorrect' => $count - $correct
        );
        return Response::json($response);
        //var_dump($data[1]);
    }

    public function getAllQuizzes($gId){
        $check = Group::isAdmin($gId,Auth::user()->id);
        return View::make('quiz.getAll')
            ->with('gId',$gId)
            ->with('check',$check);

    }

    public function DeleteQuizQuestion($questionId){
        Quiz::deleteQuestion($questionId);
        Quiz::deleteQuestionChoice($questionId);
        $response=array(
            'success' =>'success',
            'id'=>$questionId
        );
        return Response::json($response);
    }

    public function DeleteQuiz($quizId){
        Quiz::deleteQuiz($quizId);
        $response=array(
            'state' =>'success',
            'id'=>$quizId
        );
        return Response::json($response);
    }

    public function getHomequiz(){
        $last_id = Input::get('last_quiz_id');
        $quiz = Quiz::getQuizGroup(Auth::user()->id , $last_id);
        $x=0;
        $date2=Carbon\Carbon::now()->toDateTimeString();
        $ts1=strtotime($date2);
        $result=array();
        $at=array();

        foreach ($quiz as $index) {
            $ts2=strtotime($index->start_date);
            $result[$x]=$ts1-$ts2;
            if($result[$x]<60){
                $at[$x]=0;
                $result[$x]="few second ago";
            }elseif ($result[$x]>60&&$result[$x]/60<60) {
                $at[$x]=0;
                $result[$x]=floor(($result[$x]/60))." mintes ago";
            }elseif ($result[$x]/60>60&&$result[$x]/(60*60)<24) {
                $at[$x]=0;
                $result[$x]=floor(($result[$x]/(60*60)))." hour ago";
            }elseif ($result[$x]/(60*60)>24) {
                $at[$x]=date("H:i",strtotime($index->start_date)); 
                $result[$x]=floor(($result[$x]/(60*60*24)))." days ago";
            }
            $x++;
        }
        
        $response=array(
            'quizzes' => $quiz,
            'date' =>$result,
            'at' =>$at
        );
        return Response::json($response);
    }

    public function showHomeNewQuiz(){
        $last_id=Input::get('last_id');
        $quizzes=Quiz::GetNewQuiz($last_id);
        $response=array(
            'quizzes' => $quizzes
        );
        return Response::json($response);    
    }

    public function getAllUserAnswer($quiz_id,$model){
        $result = Quiz::getAllUserAnswer($quiz_id,$model);
        return Response::json($result);
       // return View::make('test')
         //   ->with('result',$result);
    }

    public function getMaxQuizId(){
        $max_quiz_id = DB::table('quiz')->max('id');
        $response=array(
            'max_id' => $max_quiz_id,
        );
        return Response::json($response);
    }
}

