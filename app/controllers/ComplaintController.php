<?php
class ComplaintController extends BaseController
{

    public function getAddComplaint($gId){

        $check = Group::isAdmin($gId,Auth::user()->id);
        return View::make('complaints.create')
            ->with('groupId',$gId)
            ->with('IsAdmin',$check);
    }

    public function postAddComplaint($gId){

        $data = array(

            'user_id'=>Auth::user()->id,
            'group_id'=>$gId,
            'title'=>Input::get('cTitle'),
            'content'=>Input::get('cContent'),
            'created_at'=>Carbon\Carbon::now()->toDateTimeString(),
        );

        Complaint::createComplaint($data);
        $response = array(
            'state'=>'success'
        );
        return Response::json($response);

    }
    public function DeleteComplaint($complaintId){
        Complaint::deleteComplaint($complaintId);

        $response=array(
            'success' =>'success',
        );
        return Response::json($response);
    }

}
