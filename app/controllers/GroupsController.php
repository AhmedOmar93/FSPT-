<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 01/04/2015
 * Time: 11:39 ص
 */

class GroupsController extends BaseController{

    public function getCreate(){
        return View::make('group.create');
    }

    public function getCreateTechnical(){
        return View::make('group.technicalGroup');
    }

    public function postCreate(){
        $filename = '';
        $extension='';
        $validator = Validator::make(Input::all(),
            array(
                'name'=>'required | max:30 | min:3 |unique:groups',
                'group_des'=>'required ',
                'group_Syllable'=>'required',
                'group_police'=>'required '
            )
        );

        if ($validator->fails()) {
            //die('Failed.');
            return Redirect::route('group-create')
                ->withErrors($validator)
                ->withInput();
        }
        else{
            if (Input::hasFile('image')) {
                // $filename = Input::file('image')->getClientOriginalName();
                // print_r($filename);
                $filename = str_random(10);
                // print_r($filename);
                $extension = Input::file('image')->getClientOriginalExtension();
                $destination_path = 'public/uploads/';
                try {
                    $file = Input::file('image')->move($destination_path, $filename.'.'.$extension);

                } catch (Exception $e) {
                     // Handle your error here.
                    // You might want to log $e->getMessage() as that will tell you why the file failed to move.
                    print_r($e->getMessage());
                }
            }
            $data = array(
                'name'=>Input::get('name'),
                'description'=>Input::get('group_des'),
                'image'=>$filename.'.'.$extension,
                'grade_police'=>Input::get('group_police'),
                'syllable'=>Input::get('group_Syllable'),
                'expireDate'=>Input::get('expire_date'),
                'create_date'=>Carbon\Carbon::now()->toDateTimeString(),
                'user_id'=>Auth::user()->id
            );
            $group_id = Group::createGroup($data);
            $member = array(
                "group_id"=>$group_id,
                "user_id"=>Auth::user()->id,
                "edit_date"=>Carbon\Carbon::now()->toDateTimeString(),
                "admin"=>1
            );
            Group::AddOneMemberInSpecificGroup($member);
            return Redirect::route('groups-show',Input::get('name'));

            /*
            $response = array(
                'state'=>'success in add group'
            );
            return Response::json($response);
            */
            //return Redirect::route('http://localhost:8000/group/show/'.Input::get('name'));
        }

    }



    public function postCreateTechnichal(){
        $filename = '';
        $extension='';
        $validator = Validator::make(Input::all(),
            array(
                'name'=>'required | max:30 | min:3 |unique:groups',
                'group_des'=>'required | max:50 | min:3'
            )
        );

        if ($validator->fails()) {
            //die('Failed.');
            return Redirect::route('technical-group-create')
                ->withErrors($validator)
                ->withInput();

          //  return Redirect::action('GroupsController@showTechnicalGroup',Input::get('name'));
          //  return Redirect::route('Technical-groups-show',Input::get('name'));
        }
        else{

            if (Input::hasFile('image')) {
                // $filename = Input::file('image')->getClientOriginalName();
                // print_r($filename);
                $filename = str_random(10);
                // print_r($filename);
                $extension = Input::file('image')->getClientOriginalExtension();
                $destination_path = 'public/uploads/';
                try {
                    $file = Input::file('image')->move($destination_path, $filename.'.'.$extension);

                } catch (Exception $e) {

                    // Handle your error here.
                    // You might want to log $e->getMessage() as that will tell you why the file failed to move.
                    print_r($e->getMessage());
                }
            }
            $data = array(
                'name'=>Input::get('name'),
                'description'=>Input::get('group_des'),
                'image'=>$filename.'.'.$extension,
                'policy'=>'public',
                'create_date'=>Carbon\Carbon::now()->toDateTimeString(),
                'user_id'=>Auth::user()->id
            );
            $group_id = Group::createGroup($data);
            $member = array(
                "group_id"=>$group_id,
                "user_id"=>Auth::user()->id,
                "edit_date"=>Carbon\Carbon::now()->toDateTimeString(),
                "admin"=>1
            );
            Group::AddOneMemberInSpecificGroup($member);
            $response = array(
                'state'=>'success'
            );
            //return Response::json($response);
            return Redirect::route('Technical-groups-show',Input::get('name'));
           // showGroup(Input::get('name'));
        }

    }


    // show group information
    public function showGroup($name){

        $group = Group::getAllGroupsByGroupName($name);
        $members = Group::getAllMembersByGroupId($group->id);
        $admins = Group::getAllAdminsByGroupId($group->id);
        $isAdmin = Group::checkMemberInGroupAdmin(Auth::user()->id,$group->id);
        $checkMember = Group::checkMemberInGroup(Auth::user()->id,$group->id);
        $groupRequest = Group::getAllMembersRequest($group->id);
        if(empty($checkMember)){$checkMember=0;}
        
        return View::make('group')->with('groupInf',$group)
            ->with('members',$members)
            ->with('admins',$admins)
            ->with('check',$checkMember)
            ->with('isAdmin',$isAdmin)
            ->with('groupRequest',$groupRequest);

    }

    // show group information
    public function showTechnicalGroup($name){

        $group = Group::getAllGroupsByGroupName($name);
        $members = Group::getAllMembersByGroupId($group->id);
        $admins = Group::getAllAdminsByGroupId($group->id);
        $checkMember = Group::checkMemberInGroup(Auth::user()->id,$group->id);
        $groupRequest = Group::getAllMembersRequest($group->id);
        
        if(empty($checkMember)){$checkMember=0;}
        
         return View::make('employeeGroup')->with('groupInf',$group)
            ->with('members',$members)
            ->with('admins',$admins)
            ->with('check',$checkMember)
            ->with('groupRequest',$groupRequest);

        
        //echo $group->id;
        //return "ahmed";
    }
    public function addMember($group_id){

        $data = Users::checkCode(Input::get('code'));
        //print_r($data);

        if($data == null){
            $response=array(
                'message' =>'this is not member in this social network please register first .'
            );
            return Response::json($response);
        }else{
            if(Input::has('radio'))
            {
                $member = array(
                    "group_id"=>$group_id,
                    "user_id"=>$data->id,
                    "edit_date"=>Carbon\Carbon::now()->toDateTimeString(),
                    "admin"=>Input::get('radio'),
                    "request"=>0
                );
                Group::AddOneMemberInSpecificGroup($member);
                $response=array(
                    'message' =>'success in adding member.'
                );
                return Response::json($response);
            }else{
                $response=array(
                    'message' =>'select the type of user member or admin .'
                );
                return Response::json($response);
            }
        }
    }


    public function removeMember($group_id){

        $data = Users::checkCode(Input::get('code'));
        //print_r($data);

        if($data == null){
            $response=array(
                'message' =>'this is not member in this social network .'
            );
            return Response::json($response);
        }else{
            if(Input::has('code'))
            {
                Group::deleteOneMemberInGroup($group_id,$data->id);
                $response=array(
                    'message' =>'success in removing member.'
                );
                return Response::json($response);
            }else{
                $response=array(
                    'message' =>'user code required .'
                );
                return Response::json($response);
            }
        }
    }

    public function request($group_id){
        $member = array(
            "group_id"=>$group_id,
            "user_id"=>Auth::user()->id,
            "edit_date"=>Carbon\Carbon::now()->toDateTimeString(),
            "admin"=>0,
            "request"=>1
        );
        Group::AddOneMemberInSpecificGroup($member);
        $response=array(
            'message' =>'success in sending request please wait to accept.'
        );
        return Response::json($response);
    }

    public function acceptRequest($group_id,$user_id){
        $member = array(
            "group_id"=>$group_id,
            "user_id"=>$user_id,
            "edit_date"=>Carbon\Carbon::now()->toDateTimeString(),
            "admin"=>0,
            "request"=>0
        );
        Group::acceptRequest($group_id,$user_id,$member);
        $response=array(
            'message' =>'success in accept.'
        );
        return Response::json($response);
    }

}