<?php 

class EventController extends BaseController{



	public function Add(){

	//	$newEvent=file_get_contents('php://input');
	//	$newEvent=json_decode($newEvent);

		$event=new Events;

		$event->title=$_POST['title'];
		$event->details=$_POST['details'];
		$event->start=$_POST['start'];
		$event->end=$_POST['end'];
		$event->allDay=true;
		$event->borderColor=$_POST['borderColor'];
		$event->backgroundColor=$_POST['borderColor'];
		$event->userId=Auth::id();
		
		if(isset($_POST['gId'])){
			$event->gId=$_POST['gId'];
		}else{
			$event->gId=NULL;
		}

		if($event->save()){
			return $event->id;
		}else{
			return 0;
		}
		

	}

	public  function getUserEvents($userId){

		return Events::where('userId','=',$userId)->get();

	}


	public static  function getAllEvents(){
		//return $_GET['start'];
		return $data=Events::with('owner')->where('gId','=',NULL)
		->join('users', 'users.id', '=', 'events.userId')->where('users.profession','=','doctor')
		->where('start','>=',$_GET['start'])->where('end','<=',$_GET['end'])->get();
	}

	public  function getAllGroupEvents(){
		//return $_GET['start'];
		return Events::with('owner')->where('gId','=',$_GET['gId'])->where('start','>=',$_GET['start'])->where('end','<=',$_GET['end'])->get();
	}

	public function UpdateEvent(){

		$updated_event=Events::find($_POST['id']);

		$updated_event->start=$_POST['start'];
		$updated_event->end=$_POST['end'];
		
		
		if($_POST['allDay']=="true" || $_POST['allDay']=="1"){
		$updated_event->allDay=1;
		}else{
		$updated_event->allDay=0;
		}
		
		
		if($updated_event->save()){
			return $updated_event->allDay;
		}else{
			return -1;
		}
	}

	public static function allUserEvents(){
		return Events::where('userId','=',Auth::id())->where('gId','=',NULL)->orderBy('id','desc')->get();
	}
	public static function allGroupEvents($gId){
		return Events::with('owner')->where('gId','=',$gId)->orderBy('id','desc')->get();
	}


	public function periodUserEvent(){
		return Events::where('userId','=',Auth::id())->where('gId','=',NULL)->where('start','>=',$_GET['start'])->where('end','<=',$_GET['end'])->get();
	}

	public function periodGroupEvent(){
		return Events::where('gId','=',$_GET['gId'])->where('start','>=',$_GET['start'])->where('end','<=',$_GET['end'])->get();
	}



	public function deleteEvent(){

		if(Events::destroy($_POST['id'])){
			return 1;
		}else{
			return 0;
		}	


	}
}

?>