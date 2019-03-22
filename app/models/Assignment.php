<?php

class Assignment {

	public static function getAssignmentGroup($group_id){
        $result=DB::table('assignment')
            ->join('users','assignment.user_id','=','users.id')
            ->join('groups','assignment.group_id','=','groups.id')
            ->where('assignment.group_id','=',$group_id)
            ->select('assignment.id','assignment.title','assignment.content','assignment.create_date','users.user_name','users.profile_picture','groups.name as group_name')
            ->orderBy('assignment.id' , 'desc')
            ->get();
        return $result;
    }

    public static function GetNewAssignment($last_id)
    {
        $result=DB::table('assignment')
            ->join('users','assignment.user_id','=','users.id')
            ->join('groups','assignment.group_id','=','groups.id')
            ->where('assignment.id','>',$last_id)
            ->select('assignment.id','assignment.title','assignment.content','users.user_name','groups.name as group_name')
            ->orderBy('assignment.id','decs')
            ->get();
        return $result;
    }

     /*  Insert single assignment to the assignment Table
    Parameters :
    $assignment : Takes the following info
    name , link , date of today , deadline , current group id , id of TA/Doctor*/
    public static function InsertAssignment ($assignment)
    {
        DB::table('assignment')->insert( $assignment);
        return true ;
    }
    /* Updates single assignment in the assignment Table
    Parameters :
    $assignmentId .
    $assignment : Takes the info to be updated*/
    public static function UpdateAssignment ($assignmentId , $assignmentUpdates)
    {
        DB::table('assignment')->where('id', '=', $assignmentId)
                                            ->lockForUpdate()
                                             ->update($assignmentUpdates);
        return true ;
    }
    /*  Deletes single assignment from the assignment Table
    Parameters :
    $assignmentId : Takes the assignment Id*/
    public static function DeleteAssignment ($assignmentId)
    {
        DB::table('assignment')->where('id', '=', $assignmentId)->delete();
    }
    /*  Select all uploaded assignments for a given group
    Parameters : groupId.*/
    public static function SelectAllAssignmentsOfGroup ($groupId)
    {
        $assignmentlist = DB::table('assignment')
        ->where('group_id', '=', $groupId)
        ->orderBy('due_date', 'DESC')
        ->get();
        return $assignmentlist;
    }
    /*  Select assignments of group by a given value
    Parameters : groupId , date*/
    public static function SelectAssignmentOfGroupBy ($groupId , $column , $operator ,$date)
    {
        $assignmentlist = DB::table('assignment')
        ->where('group_id', '=', $groupId)
        ->where( $column  , $operator , $date )
        ->orderBy('due_date', 'DESC')
        ->sharedLock()
        ->get();
        if ($assignmentlist)
            return $assignmentlist;
        else
            return "Empty";
    }
    /*  Select a specific assignment
    Parameters :
    $assignmentColumn:
    $operator : = , > , < etc ..
    $assignmentValue: value of this column */
    public static function SelectAssignment ($assignmentColumn , $operator ,$assignmentValue)
    {
        $assignment = DB::table('assignment')->where($assignmentColumn, $operator , $assignmentValue)->get();
        return $assignment ;
    }
/*  Get the record of the uploader
    */
    public static function SelectUserUpload ($aId){
        $user_id =  DB::table('assignment')
        ->where('id', '=', $aId) ;
        $user =  DB::table('users')
        ->where('id', '=', $user_id);
        return $user ;
    }
}

?>