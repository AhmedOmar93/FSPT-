<?php


class Group extends Eloquent {

    protected $table="groups";
    // Table: groups
    /*------------------------------------------------------------*/

    /**
     * @param $group
     */
    public static function createGroup($group)
    {
        return DB::table('groups')->insertGetId($group);
    }


    /*
     * return all groups 
     */
    public static function getAllGroups()
    {
        $groups = DB::table('groups')->get();
        return $groups;
    }


    /*
   * return group by group id
   */
    public static function getSpecificGroupById($id)
    {
        $group = DB::table('groups')->where('id', '=', $id)->get();
        return $group;
    }



    /**
     * return all group with specified name
     * @param $name
     * @return groups
     */
    public static function getAllGroupsByGroupName($name)
    {
        $groups = DB::table('groups')->where('name', '=', $name)->first();
        return $groups;
    }

    /**
     * return all group with specified Created User Id
     * @param $userId
     * @return groups
     */
    public static function getAllGroupsByCreatedUserId($userId)
    {
        $groups = DB::table('groups')->where('user_id', '=', $userId)->get();
        return $groups;
    }

    /**
     * @param $id
     * @param $name
     */
    public static function updateGroupName($id,$name)
    {
        DB::table('groups')
            ->where('id', $id)
            ->update(array('name'=> $name));
    }



    /**
     * @param $id
     * @param $desc
     */
    public static function updateGroupDescription($id,$desc)
    {
        DB::table('groups')
            ->where('id', $id)
            ->update(array('description'=> $desc));
    }


    /**
     * @param $id
     * @param $image
     */
    public static function updateGroupImage($id,$image)
    {
        DB::table('groups')
            ->where('id', $id)
            ->update(array('image'=> $image));
    }




    /**
     * delete specific Group
     * @param $groupId
     */
    public static function deleteGroupById($groupId)
    {
        DB::table('groups')->where('id', '=', $groupId)->delete();
    }


    // Table: group_members
    /*------------------------------------------------------------*/


    /**
     * @param $group_member
     * @return mixed
     */
    public static function AddOneMemberInSpecificGroup($group_member)
    {
        DB::table('group_members')->insert($group_member);
    }

    /**
     * @param $group_member
     * @return mixed
     */
    public static function removeOneMemberInSpecificGroup($userId,$groupId)
    {
        
    }


    /**
     * return all user members within specified Group Id
     * @param $groupId
     * @return user members
     */
    public static function getAllMembersByGroupId($groupId)
    {

        $data = DB::table('group_members')
            ->join('users','users.id','=','group_members.user_id')
            ->join('groups','groups.id','=','group_members.group_id')
            ->where('groups.id','=',$groupId)
            ->where('group_members.admin','=',0)
            ->where('group_members.request','=',0)
            ->select('users.user_name','users.user_code','users.id','users.profile_picture')
            ->get();
        return $data;


    }

    /**
     * return all user admins within specified Group Id
     * @param $groupId
     * @return user members
     */
    public static function getAllAdminsByGroupId($groupId)
    {

        $data = DB::table('group_members')
            ->join('users','users.id','=','group_members.user_id')
            ->join('groups','groups.id','=','group_members.group_id')
            ->where('groups.id','=',$groupId)
            ->where('group_members.admin','=',1)
            ->where('group_members.request','=',0)
            ->select('users.user_name','users.user_code','users.id','users.profile_picture')
            ->get();
        return $data;


    }

    public static function isAdmin($groupId,$userId){
        $data = DB::table('group_members')
            ->join('users','users.id','=','group_members.user_id')
            ->join('groups','groups.id','=','group_members.group_id')
            ->where('groups.id','=',$groupId)
            ->where('group_members.admin','=',1)
            ->where('group_members.request','=',0)
            ->where('group_members.user_id','=',$userId)
            ->select('users.user_name','users.id')
            ->first();
        return $data;

    }

    public static function getAllMembersRequest($groupId)
    {

        $data = DB::table('group_members')
            ->join('users','users.id','=','group_members.user_id')
            ->join('groups','groups.id','=','group_members.group_id')
            ->where('groups.id','=',$groupId)
            ->where('group_members.request','=',1)
            ->select('users.user_name','users.id')
            ->get();
        return $data;


    }

    /**
     * return all groups for specified user id
     * @param $userId
     * @return all groups
     */
    public static function getAllGroupsByUserId($userId)
    {
        $data = DB::table('group_members')
            ->join('groups','groups.id','=','group_members.group_id')
            ->join('users','groups.user_id','=','users.id')
            ->where('group_members.user_id','=',$userId)
            ->where('users.profession','!=','employee')
            ->select('groups.name','groups.id')
            ->get();
        return $data;

    }
    public static function getUserProject($userId)
    {
        $data = DB::table('group_members')
            ->join('groups','groups.id','=','group_members.group_id')
            ->join('users','groups.user_id','=','users.id')
            ->where('group_members.user_id','=',$userId)
            ->where('groups.is_project','=',1)
            ->select('groups.name','groups.id')
            ->get();
        return $data;

    }


    /**
     * return all groups for specified user id
     * @param $userId
     * @return all groups
     */
    public static function getAllGroupsTechnical($userId)
    {
        $data = DB::table('groups')
            ->join('users','groups.user_id','=','users.id')
            ->where('groups.policy','=','public')
            ->where('users.profession','=','employee')
            ->select('groups.name','groups.id')
            ->get();
        return $data;

    }



    /**
     * delet one Members In specific Group
     * @param $groupId
     */
    public static function deleteOneMemberInGroup($g,$m)
    {
        DB::table('group_members')
            ->where('group_id', '=' , $g)->where('user_id', '=' , $m)->delete();

    }


    /**
     * delet All Members In specific Group
     * @param $groupId
     */
    public static function deletAllMembersInGroup($groupId)
    {
        DB::table('group_members')->where('group_id', '=', $groupId)->delete();
    }





    public static function checkMemberInGroup($userId,$groupId)
    {
        $data = DB::table('group_members')->where('group_id', '=', $groupId)
            ->where('user_id', '=', $userId)
            ->where('request', '=',0)
            ->count();
        if($data > 0){
            return true;
        }
        else{
            return false;
        }
    }


    public static function checkMemberInGroupAdmin($userId,$groupId)
    {
        $data = DB::table('group_members')->where('group_id','=', $groupId)
            ->where('user_id','=', $userId)
            ->where('request','=',0)
            ->where('admin','=',1)
            ->count();
        if($data > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public static function acceptRequest($group_id,$user_id,$data){
        DB::table('group_members')
            ->where('group_id',$group_id)
            ->where('user_id',$user_id)
            ->update($data);
    }

    public  function group_members(){
        $obj=$this->hasMany('GroupMember','group_id','id');
        return $obj;
    }


}//end class Group