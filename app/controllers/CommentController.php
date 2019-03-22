<?php 

class CommentController extends BaseController{

	public function addComment(){


		$newComment=file_get_contents('php://input');
		$newComment=json_decode($newComment);

	
		$comment=new Comments;
		$comment->content=$newComment->content;
		$comment->answer_id=$newComment->answer_id;
		$comment->user_id=Auth::id();
		if($comment->save()) return $comment->id;
		else echo "fail";
		
	}


	public function delete($cId){

		if(comments::destroy($cId)){
			return "success";
		}else{	
			return "fail";
		}	


	}
}

?>
