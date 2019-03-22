<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
* Date: 02/07/2015
* Time: 10:50 ص
*/

class Survey{

    public static function createSurvey($survey)
    {
        DB::table('survey')->insert($survey);
    }


    public static function getAllSurveys(){
        $quizzes = DB::table('survey')->get();
        return $quizzes;
    }


    public static function getSurveyId($title){
        $id = DB::table('survey')
            ->where('title','=',$title)
            ->first();
        return $id;
    }


    public static function addQuestion($question){
        $id = DB::table('survey_questions')->insertGetId($question);
        return $id;
    }

    public static function addChoice($choice)
    {
        DB::table('survey_questions_answers')->insert($choice);
    }

    public static function getQuestionsWithChoices($surveyId){
        $data = DB::table('survey_questions_answers')
            ->join('survey_questions','survey_questions.id','=','survey_questions_answers.survey_questions_id')
            ->join('survey','survey.id','=','survey_questions.survey_id')
            ->where('survey.id','=',$surveyId)
            ->select('survey_questions.id','survey_questions.content','survey_questions_answers.content as choice','survey_questions_answers.id as cid','survey_questions_answers.rate as rate')
            ->get();
        return $data;
    }

    public static function updateChoice($id){
        $data = DB::table('survey_questions_answers')
            ->where('id','=',$id)
            ->first();
        if($data != null) {
            $rate = $data->rate;
            $rate++;
            DB::table('survey_questions_answers')
                ->where('id', '=', $id)
                ->update(array('rate' => $rate));
        }
    }
    public static function userAnswers($data){
        DB::table('survey_user_answers')->insert($data);
}

    public static function AlluserAnswersId($id){
       return DB::table('survey_user_answers')
           ->where('survey_id','=',$id)
           ->select('survey_user_answers.user_id')
           ->get();
    }



    public static function deleteQuestion($id){
        DB::table('Survey_questions')->where('id','=',$id)->delete();
    }

    public static function deleteSurvey($id){
        DB::table('survey')->where('id','=',$id)->delete();
    }
}