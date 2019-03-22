<?php

class QuestionController extends BaseController {

	public function add(){

		$question =file_get_contents("php://input");
		$question=json_decode($question);
		$newQuestion=new Question;
		
		$newQuestion->content=$question->content;
		$newQuestion->title=$question->title;
		$newQuestion->search_tag=$question->search_tag;
		$newQuestion->group_id=$question->group_id;
		$newQuestion->user_id=Auth::id();

		
		if($newQuestion->save()){
			return $newQuestion;
		}else{
			return "fail";
		}

	}

	public static function get_prev_questions($gId,$qId){
		$questions=Question::with('owner')->where('group_id','=',$gId)->where('id','<',$qId)->orderBy('id','desc')->get()->take(5);
		foreach ($questions as $question) {
			$question->question_time=$question->created_at->diffForHumans();
		}
		return $questions;
	}

	public static function getAll($gId){

		$questions=Question::with('owner')->where('group_id','=',$gId)->orderBy('id','desc')->get()->take(5);
		foreach ($questions as $question) {
			$question->question_time=$question->created_at->diffForHumans();
		}
		return $questions;
	}

	public function delete($qId){

		if(Question::destroy($qId)){
			return "success";
		}else{	
			return "fail";
		}	
		//return $qId;
	}

	public static function get_user_questions($user_id){
		return Question::with('groups')->where('user_id','=',$user_id)->get();
	}

	public static function get_question($qId){
		$question=Question::with('owner')->where('id','=',$qId)->first();
		$question->question_time=$question->created_at->diffForHumans();
		return $question;
	}

	public function getHomeQuestions(){
		$last_id = Input::get('last_question_id');
		$questions = Question::GetHomeQuestion(Auth::user()->id , $last_id);
		$x=0;
		$date2=Carbon\Carbon::now()->toDateTimeString();
		$ts1=strtotime($date2);
		$result=array();
		$at=array();
		foreach ($questions as $index) {
			$ts2=strtotime($index->created_at);
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
				$at[$x]=date("H:i",strtotime($index->created_at)); 
				$result[$x]=floor(($result[$x]/(60*60*24)))." days ago";
			}
			$x++;
		}
		/*
			$groups=Group::getAllGroupsByUserId(Auth::user()->id);
			$questions=array();
			$i=0;
			$date2=Carbon\Carbon::now()->toDateTimeString();
			$ts1=strtotime($date2);
			$result=array();
			$at=array();
			foreach ($groups as $group) {
				$questions[$i]=Question::with('owner')->where('group_id','=',$group->id)->orderBy('id','desc')->get();
				$i++;
			}
			$temp=array();
			$x=0;
			for ($i=0; $i < count($questions); $i++) { 
				for ($j=0; $j < count($questions[$i]); $j++) { 
					$temp[$x]=$questions[$i][$j];
					$ts2=strtotime($temp[$x]->created_at);
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
						$at[$x]=date("H:i",strtotime($temp[$x]->created_at)); 
						$result[$x]=floor(($result[$x]/(60*60*24)))." days ago";
					}
					$x++;
				}
			}

			for($i=0;$i<count($temp);$i++){
				for($j=$i+1;$j<count($temp);$j++){
					if($temp[$j]->id > $temp[$i]->id){
						$alaa=$temp[$i];
						$temp[$i]=$temp[$j];
						$temp[$j]=$alaa;
					}
				}
			}
		*/
		$response=array(
			'questions' => $questions,
			'date' =>$result,
			'at' =>$at
		);
		return Response::json($response);
	}

	public function showHomeNewQuestion(){
		$last_id=Input::get('last_id');
		$questions=Question::GetNewQuestion($last_id);
		$response=array(
			'questions' => $questions,
		);
		return Response::json($response);    
	}
	public function getMaxQuestionId(){
		$max_question_id = DB::table('question')->max('id');
		$response=array(
			'max_id' => $max_question_id,
		);
		return Response::json($response);
	}
}

?>