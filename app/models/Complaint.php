<?php


class Complaint
{

public static function createComplaint($complaint)
{
    DB::table('complaint')->insert($complaint);
}


    public static function getAllComplaintsByInGroup($groupId){
        $res = DB::table('complaint')
            ->join('users','complaint.user_id','=','users.id')
            ->where('group_id','=',$groupId)
            ->select('complaint.title','complaint.id','complaint.content','users.email','users.profile_picture','users.user_code','users.last_name','users.first_name')

            ->get();
        return $res;
    }

    public static function deleteComplaint($id){
        DB::table('complaint')->where('id','=',$id)->delete();
    }

    public static function getAllVotesWithItsOwnerByGroupId($groupId)
    {
        $AllVotes = DB::table('vote')
            ->join('users','vote.user_id','=','users.id')
            ->where('group_id', '=', $groupId)
            ->select('vote.id','vote.title','vote.content','vote.user_id','users.first_name','users.last_name','users.profile_picture')
            ->get();
        return  $AllVotes;
    }
}