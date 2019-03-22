<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 02/07/2015
 * Time: 10:39 ص
 */

class SurveyController extends BaseController{
    public function getCreateSurvey(){
        return View::make('survey.create');
    }

    public function postCreateSurvey(){
        $response = array();
        if(Input::get('surveyTitle') == null){
            $response = array(
                'state' => 'the name of survey required !'
            );
        }else {
            if (Input::get('start_date') == null || Input::get('end_date') == null) {
                $response = array(
                    'state' => 'start date and end date required!'
                );
            } else {
                $survey = array(
                    'title' => Input::get('surveyTitle'),
                    'about'=>Input::get('about'),
                    'start_at' => Input::get('start_date'),
                    'end_at' => Input::get('end_date'),
                    'created_at'=>Carbon\Carbon::now()->toDateTimeString(),
                    'user_id' => Auth::user()->id
                );
                Survey::createSurvey($survey);
                $response = array(
                    'state' => 'success'
                );
            }
        }
        return Response::json($response);

    }


    public function getAllSurveys(){
        $surveys = Survey::getAllSurveys();
        return View::make('survey.getAll')
            ->with('surveys', $surveys);
    }

    public function getCreateQuestion(){
        return View::make('survey.createQuestion');
    }
    public function postCreateQuestion(){

        $count = 0;
        $response =array();
        foreach(Input::all() as $val ){
            $count++;
        }
        if(!Input::get('question')){
            $response = array(
                'state' => 'please question content.'
            );
            return Response::json($response);
        }
        $count = $count - 3;
        $data = array(
            'content'=>Input::get('question'),
            'created_at'=>Carbon\Carbon::now()->toDateTimeString(),
            'survey_id'=>Survey::getSurveyId(Input::get('surveyTitle'))->id,
            'user_id'=>Auth::user()->id
        );

        if(Input::has('choice0')){
                $id = Survey::addQuestion($data);
                for ($i = 0; $i < $count; $i++) {
                        $choice = array(
                            'content' => Input::get('choice' . $i),
                            'survey_questions_id' => $id,
                            'rate'=>0
                        );
                    Survey::addChoice($choice);
                }
                $response = array(
                    'state' => 'success'
                );
            }else{
                $response = array(
                    'state' => 'please select the choice.'
                );
            }

        return Response::json($response);
    }


    public function getAllQuestionsChoices($survey_id){
        $check = 0;
        $data = Survey::getQuestionsWithChoices($survey_id);
        $users = Survey::AlluserAnswersId($survey_id);
        if($users != null){
            foreach($users as $id){
                if($id->user_id == Auth::user()->id)
                    $check = 1;
            }
        }
       // $checkAdmin = Group::checkMemberInGroupAdmin(Auth::user()->id,$group_id);
        return View::make('survey.show')
            ->with('questions',$data)
            ->with('check',$check)
            ->with('survey_id',$survey_id);
    }

    public function getAllQuestionsChoicesWithRate($survey_id){
        $data = Survey::getQuestionsWithChoices($survey_id);
        return View::make('survey.surveyResult')
            ->with('questions',$data)
            ->with('survey_id',$survey_id);
    }

    public function DeleteSurvey($SurveyId){
        Survey::deleteSurvey($SurveyId);
        $response=array(
            'success' =>'success',
            'id'=>$SurveyId
        );
        return Response::json($response);
    }

    public function DeleteSurveyQuestion($questionId){
        Survey::deleteQuestion($questionId);

        $response=array(
            'state' =>'success',
            'id'=>$questionId
        );
        return Response::json($response);
    }


    public function postQuizAnswer($survey_id){

        //return $count;
        foreach(Input::all() as $val ){
           Survey::updateChoice($val);
        }
        $data = array(
            'user_id'=>Auth::user()->id,
            'survey_id'=>$survey_id,
            'created_at'=>Carbon\Carbon::now()->toDateTimeString()
        );
       Survey::userAnswers($data);
        $response=array(
            'state' =>'thanks'
        );
        return Response::json($response);
    }

}