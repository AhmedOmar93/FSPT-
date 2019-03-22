<?php

	class AnswersController extends BaseController{

		public function add(){
			$answer=file_get_contents('php://input');
			$answer=json_decode($answer);
			
			$newAnswer=new Answer;

			$newAnswer->content=$answer->content;
			$newAnswer->question_id=$answer->question_id;
			$newAnswer->user_id=Auth::id();
			$newAnswer->rate=0;


			if($newAnswer->save())
			
				return $newAnswer;
			else
				echo "fail";
		}

		public static function getAll($qId){

			$answs=Answer::with('rates','owner','comments.owner')
			->where('question_id', '=', $qId)->orderBy('is_right','desc')->orderBy('id','asc')->orderBy('rate','desc')->get();
			
			//$answs=$answs->toJson();
			$userID=Auth::id();
			foreach ($answs as $key => $value) {

				$answs[$key]->answer_time=$answs[$key]->created_at->diffForHumans();

				foreach ($answs[$key]->rates as $rate){
					if($rate->user_id==$userID){
						$answs[$key]['checked']=true;
						break;
					}
				}
			}

			return $answs;
		}


		public function delete($aId){

			if(Answer::destroy($aId)){
				return "success";
			}else{	
				return "fail";
			}	


		}

		public function rate(){

			$answer=file_get_contents('php://input');
			$answer=json_decode($answer);
			
			$updated_answer=Answer::find($answer->id);
			if($answer->target==1){
				$updated_answer->rate++;
			}else if($answer->target==0){
				$updated_answer->rate--;
			}
			if($updated_answer->save()){
				$user_rate=new AnswerUserRate;
				$user_rate->answer_id=$answer->id;
				$user_rate->user_id=Auth::id();
				$user_rate->save();
			}

		}

		public function mark_right($aId){

			$updated_answer=Answer::find($aId);
			$question=Question::find($updated_answer->question_id);
			if(Auth::id()==$question->user_id){
				$updated_answer->is_right=1;
				if($updated_answer->save()) return "success";
				else return "fail";

			}else{
				return "fail";
			}

		}


	}

 ?>