<?php 

class ProjectController extends BaseController{

	public function Add(){

		$project =file_get_contents("php://input");
		$project=json_decode($project);

		$group=new Group;
		$group->name=$project->name;
		$group->description=$project->description;
		$group->expireDate=$project->expireDate;
		$group->user_id=Auth::id();
		$group->is_project=true;


		if($group->save())
			return $group->id;
		else
			return -1;

	}

	public static function get_user_projects(){
		return Group::getUserProject(Auth::id());
	}

	public static function view(){
		//$project=Group::where('is_project','=',1)->where('id','=',$pId)->get()->first();
		//return $project;
		return View::make('project/view');
	}

	public static function getMembersWithTasks($pId){
		User::$group_id=$pId;
		$project=Group::with('group_members.details.tasks')->where('id',$pId)->where('is_project',1)->get();
		
		foreach ($project[0]->group_members as $key => $value) {
			$project[0]->group_members[$key]->groupMemberTime=$project[0]->group_members[$key]->created_at->diffForHumans();
			if($project[0]->group_members[$key]->user_id==Auth::id()){
				if($project[0]->group_members[$key]->admin==1){
					$project[0]->is_admin=true;
				}
			}
			foreach ($project[0]->group_members[$key]->details->tasks as $key2 => $value2) {
				$project[0]->group_members[$key]->details->tasks[$key2]->taskTime=$project[0]->group_members[$key]->details->tasks[$key2]->created_at->diffForHumans();
			}
			# code...
			
		}
		
		return $project;
	}

	public function addNewMember($userCode,$gId){
		$user=User::where('user_code',$userCode)->get();
		
		if($user->count()>0){
			$member=GroupMember::where('user_id',$user[0]->id)->where('group_id',$gId);
			if($member->count()==0){
				$member=new GroupMember;
				$member->user_id=$user[0]->id;
				$member->group_id=$gId;
				$member->admin=0;
				if($member->save()){
					$member->details=$user[0];
					return $member;
				}else{
					return 0; // fail
				}
			}else{
				return -2; // alreay member
			}
		}else{
			return -1; //wrong user code
		}
	}

	public function addNewTask(){
		$task =file_get_contents("php://input");
		$task=json_decode($task);
		
		$newTask=new Task;
		$newTask->name=$task->name;
		$newTask->description=$task->description;
		$newTask->start_at=$task->start_at;
		$newTask->end_at=$task->end_at;
		$newTask->finished=0;
		$newTask->user_id=$task->user_id;
		$newTask->group_id=$task->group_id;

		if($newTask->save()) return $newTask;
		else return -1;

		//print_r($newTask);
	}

	public function markTaskDone($taskId){

		$task=Task::find($taskId);

		$task->finished=1;

		if($task->save()) return 1;
		else return 0;
	}

	public function markTaskUnDone($taskId){

		$task=Task::find($taskId);

		$task->finished=0;

		if($task->save()) return 1;
		else return 0;
	}

	public function deleteTask($taskId){

		if(Task::destroy($taskId)) return 1;
		else return 0;
	}



}