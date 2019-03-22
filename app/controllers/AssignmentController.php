<?php
class AssignmentController extends BaseController{

    public function getHomeAssignment(){
        $groups=Group::getAllGroupsByUserId(Auth::user()->id);
        $assignments=array();
        $i=0;
        $date2=Carbon\Carbon::now()->toDateTimeString();
        $ts1=strtotime($date2);
        $result=array();
        $at=array();
        foreach ($groups as $group) {
            $assignments[$i]=Assignment::getAssignmentGroup($group->id);
            $i++;
        }
        $temp=array();
        $x=0;
        for ($i=0; $i < count($assignments); $i++) { 
            for ($j=0; $j < count($assignments[$i]); $j++) { 
                $temp[$x]=$assignments[$i][$j];
                $ts2=strtotime($temp[$x]->create_date);
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
                    $at[$x]=date("H:i",strtotime($temp[$x]->create_date)); 
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
        $response=array(
            'assignments' => $temp,
            'date' =>$result,
            'at' =>$at
        );
        return Response::json($response);
    }

    public function showHomeNewAssignment(){
        $last_id=Input::get('last_id');
        $assignments=Assignment::GetNewAssignment($last_id);
        $response=array(
            'assignments' => $assignments,
        );
        return Response::json($response);    
    }

    /*|Assignment Create|*/
    public function getAssignmentAdmin($gId){
        
        //$gId = 19;
        
        //$Assignment_Views='Assignment.create1';
        $groups=Group::getAllGroupsByUserId(Auth::user()->id);
        $check = Group::isAdmin($gId,Auth::user()->id);
        $assignmentList = Assignment::SelectAssignmentOfGroupBy($gId,'due_date','>',date("Y-m-d H:i:s"));
        $category = "Ongoing";
         $empty=false ;
        if (    $assignmentList == "Empty" || empty($assignmentList)    )
            $empty=true ;

        //return $assignmentList;
        return View::make('Assignment.create2')
                    ->with('gId',$gId)
                    ->with('assignmentsList',$assignmentList) 
                    ->with('category' , $category)
                    ->with('IsAdmin',$check)
                    ->with('isEmpty',$empty);
                    
                   // return "worked";
   // return "ahmed";
    }

    public function postAssignmentAdmin(){/*data will be sent here using javascript using ajax */
        $data=$_POST['data'];
        /*
        $data = array(
            'title' => $_POST['title'],
            'link' => $_POST['link'],
            'due_date' => $_POST['due_date'],
            'create_date' => date('Y-m-d H:i:s'),
            'user_id'=>Auth::user()->id,
            'group_id'=> $_POST['group_id'],
            'content'=> $_POST['content']
            ); 
        */
        $data['create_date']=date('Y-m-d H:i:s');
        $data['user_id']=Auth::id();
        
        
        $done = Assignment::InsertAssignment ($data);
        $response = array(
            'state'=>'success in add Complaint'
        );
        return Response::json($response);
        
        //return $data;

        }

    public function postAssignmentCategoryAdmin($gId,$target){
        // $gId = 19;

        $selectBy=$target;
        $Assignment_Views='Assignment.create2';
        $groups=Group::getAllGroupsByUserId(Auth::user()->id);
        $check = Group::isAdmin($gId,Auth::user()->id);
        //$selectBy = Input::get('SelectBy');
        $category="Ongoing" ;
            switch ($selectBy) {
                case 1:
                    $assignmentList= Assignment::SelectAssignmentOfGroupBy($gId,'due_date','>',date("Y-m-d H:i:s"));
                    break;
                case 2:
                    $assignmentList = Assignment::SelectAssignmentOfGroupBy($gId,'due_date','<',date("Y-m-d H:i:s"));
                    $category="Reached Deadline" ;
                    break;
                case 3:
                    $assignmentList = Assignment::SelectAssignmentOfGroupBy ($gId,"user_id" , "=" , Auth::user()->id);
                    $category="Uploaded By Me" ;
                    break;
                case 4:
                    $assignmentList = Assignment::SelectAssignmentOfGroupBy ($gId,"user_id" , "<>" , Auth::user()->id);
                    $category="Uploaded By Others" ;
                    break;
                default:
                    $assignmentList = Assignment::SelectAssignmentOfGroupBy($gId,'due_date','>',date("Y-m-d H:i:s"));
                    break;
            }
            $empty=false ;
        if (    $assignmentList == "Empty" || empty($assignmentList)    )
            $empty=true ;          
        
        return View::make($Assignment_Views)
                    ->with('gId',$gId)
                    ->with('assignmentsList',$assignmentList) 
                    ->with('category' , $category)
                    ->with('IsAdmin',$check)
                    ->with('isEmpty',$empty);

                    //return $assignmentList." This is empty ";
    }

    public function getViewMoreAdmin($gId,$aId){
         $gId = 19;
        $Assignment_Views='Assignment.viewInfo';
        $check = Group::isAdmin($gId,Auth::user()->id);
        $assignmentMore = Assignment::SelectAssignment ( 'id' , '=' ,$aId);
        return View::make($Assignment_Views)
                    ->with('gId',$gId)
                    ->with('More',$assignmentMore) 
                    ->with('IsAdmin',$check);
    }
    public function postViewMoreAdmin($aId){
    $data = array(
            'title' => Input::get('resultName'),
            'link' => Input::get('resultUrl'),
            'due_date' => Input::get('DueDate'),
            'create_date' => date('Y-m-d H:i:s'),
            'user_id'=>Auth::user()->id,
            'content'=> Input::get('resultContent')
            );
        $done = Assignment::UpdateAssignment($aId , $data);
        $response = array(
            'state'=>'success daze (**)v'
        );
        return Response::json($response);     
    }
    /*
    public function postCorrectSolution($aId){
    $data = array(
            'title' => Input::get('resultName'),
            'link' => Input::get('resultUrl'),
            'due_date' => Input::get('DueDate'),
            'create_date' => date('Y-m-d H:i:s'),
            'user_id'=>Auth::user()->id,
            'content'=> Input::get('resultContent')
            );
        $done = Assignment::UpdateAssignment($aId , $data);
        $response = array(
            'state'=>'sensei success daze (**)v'
        );
        return Response::json($response);
    }*/
}

?>