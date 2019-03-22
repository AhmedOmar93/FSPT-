<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 02/04/2015
 * Time: 10:11 م
 */

class Quiz {
    public static function getAllSubject(){
        $subjects = DB::table('subjects')->get();
        return $subjects;
     }

    public static function getSubjectId($name){
        $id = DB::table('subjects')->where('name','=',$name)->first();
        return $id;
    }


    public static function getQuizId($name,$gid){
        $id = DB::table('quiz')
            ->where('name','=',$name)
            ->where('group_id','=',$gid)
           ->first();
        return $id;
    }

    public static function getAllQuizzes($groupId){
        $quizzes = DB::table('quiz')->where('group_id','=',$groupId)->get();
        return $quizzes;
    }

    public static function getAllQuizzesByIngroup($groupId){
        $quizzes = DB::table('quiz')->where('group_id','=',$groupId)->get();
        return $quizzes;
    }

    public static function addQuestion($question){
        $id = DB::table('quize_question')->insertGetId($question);
        return $id;
    }

    public static function createChoice($choice)
    {
        DB::table('quize_question_choice')->insert($choice);
    }

    public static function createQuiz($quiz)
    {
        DB::table('quiz')->insert($quiz);
    }



    public static function getQuizQuestionsWithChoices($group_id,$quiz_id,$model){
        $data = DB::table('quize_question_choice')
            ->join('quize_question','quize_question.id','=','quize_question_choice.question_id')
            ->join('quiz','quiz.id','=','quize_question.quiz_id')
            ->where('quiz.group_id','=',$group_id)
            ->where('quize_question.quiz_id','=',$quiz_id)
            ->where('quize_question.model','=',$model)
            ->select('quize_question.id','quize_question.content','quize_question_choice.content as choice' )
            ->get();
        return $data;
    }

    public static function getQuizQuestionsWithCorrectChoices($group_id,$quiz_id,$model){
        $data = DB::table('quize_question_choice')
            ->join('quize_question','quize_question.id','=','quize_question_choice.question_id')
            ->join('quiz','quiz.id','=','quize_question.quiz_id')
            ->where('quiz.group_id','=',$group_id)
            ->where('quize_question.quiz_id','=',$quiz_id)
            ->where('quize_question.model','=',$model)
            ->where('quize_question_choice.answer','=','true')
            ->select('quize_question_choice.content as choice' )
            ->get();
        return $data;
    }

    public static function deleteQuestion($id){
        DB::table('quize_question')->where('id','=',$id)->delete();
    }

    public static function deleteQuestionChoice($id){
        DB::table('quize_question_choice')->where('question_id','=',$id)->delete();
    }
    public static function deleteQuiz($id){
        DB::table('quiz')->where('id','=',$id)->delete();
    }

    public static function getQuizGroup($user_id , $last_id){
        $results = DB::select('SELECT q.id,q.name as quiz_name,u.user_name,u.profile_picture,u.user_code,g.name as group_name,q.start_date FROM quiz q left outer join users u on q.user_id = u.id left outer join groups g on q.group_id = g.id WHERE group_id in (select distinct group_id from group_members where user_id = :id and q.id <= :last_id) order by q.id desc LIMIT 5', ['id' => $user_id , 'last_id' => $last_id]);
        return $results;
    }

    public static function GetNewQuiz($last_id)
    {
        $result=DB::table('quiz')
            ->join('users','quiz.user_id','=','users.id')
            ->join('groups','quiz.group_id','=','groups.id')
            ->where('quiz.id','>',$last_id)
            ->select('quiz.id','quiz.name as quiz_name','users.user_name','groups.name as group_name')
            ->orderBy('quiz.id','decs')
            ->get();
        return $result;
    }




    // table user Answer

    public static function addUserAnswer($answer){
        DB::table('quiz_user_answer')->insert($answer);
    }

    public static function getAllUserAnswer($quiz_id,$model){
        $result=DB::table('quiz_user_answer')
            ->join('users','quiz_user_answer.user_id','=','users.id')
            ->join('quiz','quiz_user_answer.quiz_id','=','quiz.id')
            ->where('quiz_user_answer.quiz_id','=',$quiz_id)
            ->where('quiz_user_answer.module','=',$model)
            ->select('quiz.name as quiz_name','users.profile_picture','users.user_name as user_name','quiz_user_answer.correct as correct','quiz_user_answer.incorrect as incorrect')
            ->get();
        return $result;
    }

}