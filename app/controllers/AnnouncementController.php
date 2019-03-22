<?php
class AnnouncementController extends BaseController {
	
	public function Announcement(){
		$groups=Group::getAllGroupsByUserId(Auth::user()->id);
		return View::make('Announcement.showannouncement')
			->with('groups',$groups);
	}		
				
	public function showAnnouncement(){
		$last_id = Input::get('announcement_id');
		$announcements=Announcement::SelectAnnouncementBy('user_id','=',Auth::user()->id ,$last_id);
	  	$date2=Carbon\Carbon::now()->toDateTimeString();
		$ts1=strtotime($date2);
		$result=array();
		$at=array();
	  	for ($i=0; $i < count($announcements); $i++) { 
	  		$ts2=strtotime($announcements[$i]->create_date);
			$result[$i]=$ts1-$ts2;
			if($result[$i]<60){
				$at[$i]=0;
				$result[$i]="few second ago";
			}elseif ($result[$i]>60&&$result[$i]/60<60) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/60))." mintes ago";
			}elseif ($result[$i]/60>60&&$result[$i]/(60*60)<24) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/(60*60)))." hour ago";
			}elseif ($result[$i]/(60*60)>24) {
				$at[$i]=date("H:i",strtotime($announcements[$i]->create_date)); 
				$result[$i]=floor(($result[$i]/(60*60*24)))." days ago";
			}
	  	}
	  	$response=array(
	  		'announcement' =>$announcements,
	  		'user' =>Auth::user(),
	  		'date' =>$result,
			'at' =>$at

	  	);
	  	return Response::json($response);
	}		
				
	public function postAddAnnouncement(){
	  if(Request::ajax()){
		  $userData=array(
		  'content'=>Input::get('content'),
		  'title'=>Input::get('title'),
		  'create_date'=>Carbon\Carbon::now()->toDateTimeString(),
		  'user_id'=>Auth::user()->id,
		  'like'=>0
	  );
	  $rules=array(
		  'title'=>'required',
		  'content'=>'required'
	  );
	  $validator = Validator::make($userData,$rules);
	  if($validator->fails()){
		  return Response::json(array(
			  'fail' => true,
			  'errors' => $validator->getMessageBag()->toArray()
		  ));
		  }//end if validator
		  else{
			  $announce_id = Announcement::InsertAnnouncement($userData);
			  if(Input::get('groupselection')!="ChooseGroup"){
			  $announcement_direction=array(
					  'group_id'=>Input::get('groupselection'),
					  'announcement_id'=>$announce_id
					  );
				  Announcement::InsertAnnouncementGroup($announcement_direction);
			  }//end if group
			  if(Input::get('levelselection')!="ChooseLevel"){
				  $level=Input::get('levelselection');
				  $users=Users::getAllUsersByLevel($level);
				  foreach($users as $user){
					  $announcement_direction=array(
						  'user_id'=>$user->id,
						  'announcement_id'=>$announce_id
						  );
					  Announcement::InsertAnnouncementUser($announcement_direction);
					  }//end foreach
				  }//end if level
		  }//end else validator
		  $response=array(
			  'user' => Auth::user()->user_name,
			  'profile_picture' => Auth::user()->profile_picture,
			  'user_code' => Auth::user()->user_code,
			  'id' => $announce_id
		  );
		  return Response::json($response);
		}//end ajax request
	}//end function
			
	public function deleteAnnouncement($id){
	  Announcement::DeleteAnnouncement($id);
	  $response=array(
			  'success' => true
		  );
		  return Response::json($response);
	}
			
	public function editAnnouncement($id){
		$announcement=Announcement::SelectAnnouncementBy('id','=',$id,$id);
		$response=array(
			'announcement' =>$announcement,
			'current_user' =>Auth::user(),
		);
		return Response::json($response);
	}
		
	public function postEditeAnnouncement($id){	
		$announcement=array(
			'content'=>Input::get('content'),
			'title'=>Input::get('title'),
			'create_date'=>Carbon\Carbon::now()->toDateTimeString()
		);
		Announcement::UpdateAnnouncement($id,$announcement);
		$response=array(
			'id' => $id,
			'edit_user' =>Auth::user()
		);
		return Response::json($response);
	}

	public function showGroupAnnouncement(){
	  $groups=Group::getAllGroupsByUserId(Auth::user()->id);//groups user join(id and name)
	  $result=array();
	  $groups_name=array();
	  $i=0;
	  foreach($groups as $group){
		  $result[$i]=$group->name;
		  $i++;
		  $announcements_ids=Announcement::SelectAnnouncementOrGroup('group_id','=',$group->id);
		  foreach($announcements_ids as $announcement_id){
			   $announcement=Announcement::SelectAnnouncementBy('id','=',$announcement_id->announcement_id);//announcement title and content
			   $user=User::find($announcement[0]->user_id);//user name
			   $result[$i]=$user->user_name;
			   $i++;
			   $result[$i]=$announcement[0]->title;
			   $i++;
			   $result[$i]=$announcement[0]->content;
			   $i++;
			  }
			   $result[$i]='//';
			   $i++;
		  }
		  $response=array(
			  'result' => $result
		  );
		  return Response::json($response);       
	  }
			
	public function showLevelAnnouncement(){
		$last_id = Input::get('last_announcement_id');
		$announcements=Announcement::getLevelAnnouncement(Auth::user()->id , $last_id);
		$i=0;
		$date2=Carbon\Carbon::now()->toDateTimeString();
		$ts1=strtotime($date2);
		$result=array();
		$at=array();
		foreach($announcements as $index){
			$ts2=strtotime($index->create_date);
			$result[$i]=$ts1-$ts2;
			if($result[$i]<60){
				$at[$i]=0;
				$result[$i]="few second ago";
			}elseif ($result[$i]>60&&$result[$i]/60<60) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/60))." mintes ago";
			}elseif ($result[$i]/60>60&&$result[$i]/(60*60)<24) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/(60*60)))." hour ago";
			}elseif ($result[$i]/(60*60)>24) {
				$at[$i]=date("H:i",strtotime($index->create_date)); 
				$result[$i]=floor(($result[$i]/(60*60*24)))." days ago";
			}
			$i++;
		}
		$response=array(
			'announcement' => $announcements,
			'level' => Auth::user()->level,
			'date' =>$result,
			'at' =>$at
		);
		return Response::json($response);    
	}
		
	public function homeAnnouncement(){
		return View::make('home');
	}

	public function chichatNews(){
		return View::make('news');
	}
		
	public function showHomeAnnouncement(){
		$last_id_group = Input::get('announcement_id_group');
		$last_id_level = Input::get('announcement_id_level');
		$announcement_group=Announcement::getAnnouncementGroup(Auth::user()->id , $last_id_group);
		$announcement_level=Announcement::getAnnouncementLevel(Auth::user()->level , $last_id_level);
		$i=0;
		$date2=Carbon\Carbon::now()->toDateTimeString();
		$ts1=strtotime($date2);
		$result=array();
		$at=array();
		$temp=array();
		foreach ($announcement_group as $index) 
		{
			$index->checks = 0;
			$ts2=strtotime($index->create_date);
			$result[$i] = $ts1 - $ts2;
			if($result[$i] < 60){
				$at[$i] = 0;
				$result[$i] = "few second ago";
			}elseif ($result[$i]>60&&$result[$i]/60<60) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/60))." mintes ago";
			}elseif ($result[$i]/60>60&&$result[$i]/(60*60)<24) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/(60*60)))." hour ago";
			}elseif ($result[$i]/(60*60)>24) {
				$at[$i]=date("H:i",strtotime($index->create_date)); 
				$result[$i]=floor(($result[$i]/(60*60*24)))." days ago";
			}
			$temp[$i] = $index;
			$i++;
		}

		foreach ($announcement_level as $index) 
		{
			$index->checks = 1;
			$ts2=strtotime($index->create_date);
			$result[$i]=$ts1-$ts2;
			if($result[$i]<60){
				$at[$i]=0;
				$result[$i]="few second ago";
			}elseif ($result[$i]>60&&$result[$i]/60<60) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/60))." mintes ago";
			}elseif ($result[$i]/60>60&&$result[$i]/(60*60)<24) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/(60*60)))." hour ago";
			}elseif ($result[$i]/(60*60)>24) {
				$at[$i]=date("H:i",strtotime($index->create_date)); 
				$result[$i]=floor(($result[$i]/(60*60*24)))." days ago";
			}
			$temp[$i] = $index;
			$i++;
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

		$response=array(
			'test' => $announcement_group,
			'test2' => $announcement_level,
			'announcements' => $temp,
			'date' =>$result,
			'at' =>$at
		);
		return Response::json($response);    

		
	}

	public function showHomeNewAnnouncement(){
		$last_id=Input::get('last_id');
		$announcements=Announcement::GetNewAnnouncement($last_id);
		$response=array(
			'announcements' => $announcements,
		);
		return Response::json($response);    
	}

	
	
	public function getAnnouncementGroup(){
		date_default_timezone_set('UTC');
		$i=0;
		$date2=Carbon\Carbon::now()->toDateTimeString();
		$ts1=strtotime($date2);
		$result=array();
		$at=array();
		$group_id=Input::get('group_id');
		$announcement_id=Input::get('announcement_id');
		$announcements=Announcement::GetGroupAnnouncement($announcement_id,$group_id);
		foreach($announcements as $announcement)
		{
			$ts2=strtotime($announcement->create_date);
			$result[$i]=$ts1-$ts2;
			if($result[$i]<60){
				$at[$i]=0;
				$result[$i]="few second ago";
			}elseif ($result[$i]>60&&$result[$i]/60<60) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/60))." mintes ago";
			}elseif ($result[$i]/60>60&&$result[$i]/(60*60)<24) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/(60*60)))." hour ago";
			}elseif ($result[$i]/(60*60)>24) {
				$at[$i]=date("H:i",strtotime($announcement->create_date)); 
				$result[$i]=floor(($result[$i]/(60*60*24)))." days ago";
			}
			$i++;
		}
		$response=array(
			'announcements' => $announcements,
			'date' =>$result,
			'at' =>$at
		);
		return Response::json($response);  	
	}

	public function getMaxAnnouncementId(){

		$id=Announcement::GetMaxId();
		$response=array(
			'id' =>$id
	  	);
	  	return Response::json($response);
	}


	public function postAddCommentAnnouncement(){

		$content = Input::get('content');
		$announcement_id = Input::get('announcement_id');
		Announcement::AddComment(Auth::user()->id,$announcement_id,$content);
		$response=array();
		return Response::json($response);
	}

	public function getAnnouncementComment(){

		$announcement_id = Input::get('announcement_id');
		$result = Announcement::GetAnnouncementComment($announcement_id);
		$response=array(
			'comments' => $result
			);
		return Response::json($response);
	}

	public function postLikeAnnouncement(){
		$announcement_id = Input::get('announcement_id');
		$user_id = Input::get('user_id');
		$result = Announcement::insertLike($announcement_id , $user_id);
		Announcement::update_like($announcement_id);
		$response=array(
			'result' => $result
			);
		return Response::json($response);
	}



	public function chichatNewsGetAll(){
		$i=0;
		$date2=Carbon\Carbon::now()->toDateTimeString();
		$ts1=strtotime($date2);
		$result=array();
		$at=array();
		$last_id = Input::get('last_announcement_id');
		$announcement = Announcement::getAllChichat($last_id);
		foreach ($announcement as $index) {
			$ts2=strtotime($index->create_date);
			$result[$i]=$ts1-$ts2;
			if($result[$i]<60){
				$at[$i]=0;
				$result[$i]="few second ago";
			}elseif ($result[$i]>60&&$result[$i]/60<60) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/60))." mintes ago";
			}elseif ($result[$i]/60>60&&$result[$i]/(60*60)<24) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/(60*60)))." hour ago";
			}elseif ($result[$i]/(60*60)>24) {
				$at[$i]=date("H:i",strtotime($index->create_date)); 
				$result[$i]=floor(($result[$i]/(60*60*24)))." days ago";
			}
			$i++;
		}
		$response=array(
			'announcement' => $announcement,
			'date' =>$result,
			'at' =>$at
			);
		return Response::json($response);
	}

	public function chichatNewsGetAllUser(){
		$i=0;
		$date2=Carbon\Carbon::now()->toDateTimeString();
		$ts1=strtotime($date2);
		$result=array();
		$at=array();
		$last_id = Input::get('last_announcement_id');
		$user_id = Input::get('user_id');
		$announcement = Announcement::getAllChichatUser($last_id,$user_id);
		foreach ($announcement as $index) {
			$ts2=strtotime($index->create_date);
			$result[$i]=$ts1-$ts2;
			if($result[$i]<60){
				$at[$i]=0;
				$result[$i]="few second ago";
			}elseif ($result[$i]>60&&$result[$i]/60<60) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/60))." mintes ago";
			}elseif ($result[$i]/60>60&&$result[$i]/(60*60)<24) {
				$at[$i]=0;
				$result[$i]=floor(($result[$i]/(60*60)))." hour ago";
			}elseif ($result[$i]/(60*60)>24) {
				$at[$i]=date("H:i",strtotime($index->create_date)); 
				$result[$i]=floor(($result[$i]/(60*60*24)))." days ago";
			}
			$i++;
		}
		$response=array(
			'announcement' => $announcement,
			'date' =>$result,
			'at' =>$at
			);
		return Response::json($response);
	}



}//end of class


