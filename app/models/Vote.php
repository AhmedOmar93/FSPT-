<?php


class Vote {



    // Table: vote
    /*------------------------------------------------------------*/

    /*
    * @param $vote
     */
    public static function createVote($vote)
    {
        if( $id = DB::table('vote')->insertGetId($vote))
            return $id;
        else {
            return false;
        }
    }


    /*
        * return All votes by group id
        * @param $groupId
        */
    public static function getAllVotesByGroupId($groupId)
    {
        $AllVotes = DB::table('vote')->where('group_id', '=', $groupId)->get();
        return  $AllVotes;
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

    /*
      *
      */
    public static function getVoteWithChoicesByVoteId($vote_id){
        $data = DB::table('vote_choice')
            ->join('vote','vote.id','=','vote_choice.vote_id')
            ->where('vote.id','=',$vote_id)
            ->select('vote.title','vote.content','vote_choice.description','vote_choice.rate')
            ->get();
        return $data;
    }


    /*
   * return vote info By Vote title
    * @param $vote info
   */
    public static function getVoteInfoByVoteTitle($vote_title)
    {
        $desired_vote = DB::table('vote')->where('title', '=', $vote_title)->get();
        return  $desired_vote;
    }





    /*
    * return All votes by user id
     * @param $userId
    */
    public static function getAllVotesByUserId($userId)
    {
        $AllVotes = DB::table('vote')->where('user_id', '=', $userId)->get();
        return  $AllVotes;
    }

    /*
    * return vote info By Vote Id
     * @param $voteId
    */
    public static function getVoteInfoByVoteId($voteId)
    {
        $desired_vote = DB::table('vote')->where('id', '=', $voteId)->get();
        return  $desired_vote;
    }




    /**
     * @param $id
     * @param $title
     */
    public static function updateVoteTitle($id,$title)
    {
        DB::table('vote')
            ->where('id', $id)
            ->update(array('title'=> $title));
    }


    /**
     * @param $id
     * @param $content
     */
    public static function updateVoteContent($id,$content)
    {
        DB::table('vote')
            ->where('id', $id)
            ->update(array('content'=> $content));
    }


    /*
     * delete specific vote
     * @param $voteId
     */
    public static function deleteVoteById($voteId)
    {
        DB::table('vote')->where('id', '=', $voteId)->delete();

    }

    /*
    * delete All votes by group id
    * @param $groupId
    */
    public static function deleteAllVotesByGroupId($groupId)
    {
        DB::table('vote')->where('group_id', '=', $groupId)->delete();

    }



    /*
    * dalete All votes by user id
     * @param $userId
    */
    public static function deleteAllVotesByUserId($userId)
    {

        DB::table('vote')->where('user_id', '=', $userId)->delete();

    }



    // Table: vote_choice
    /*------------------------------------------------------------*/



    /**
     * @param $choice
     */
    public static function createChoice($choice)
    {
        DB::table('vote_choice')->insert($choice);
    }



    /*
     *@param $choiceId
     * return all choice info By choice Id  
     */
    public static function getChoiceInfoBychoiceId($choiceId)
    {
        $desired_choice = DB::table('vote_choice')->where('id', '=', $choiceId)->first();
        return  $desired_choice;
    }


    /*
         *@param $choiceId
         * return all choice info By vote Id
         */
    public static function getAllChoicesByVoteId($voteId)
    {
        $desired_choice = DB::table('vote_choice')->where('vote_id', '=', $voteId)
            ->get();
        return  $desired_choice;
    }



    /*
     *@param $choiceId
     * return rate of choice By choice Id
     */
    public static function getChoiceRateBychoiceId($choiceId)
    {
        $rate=Vote::getRateOfUsersForSpecificChoice($choiceId);
        return  $rate;
        //ÃƒËœÃ‚Â§Ãƒâ„¢Ã¢â‚¬Å¾Ãƒâ„¢Ã¢â‚¬Â¦Ãƒâ„¢Ã¯Â¿Â½ÃƒËœÃ‚Â±Ãƒâ„¢Ã‹â€ ÃƒËœÃ‚Â¶ ÃƒËœÃ‚Â£ÃƒËœÃ‚Â¹Ãƒâ„¢Ã¢â‚¬Â¦Ãƒâ„¢Ã¢â‚¬Å¾  insert Ãƒâ„¢Ã¯Â¿Â½Ãƒâ„¢Ã…Â  ÃƒËœÃ‚Â§Ãƒâ„¢Ã¢â‚¬Å¾ÃƒËœÃ‚Â¬ÃƒËœÃ‚Â¯Ãƒâ„¢Ã‹â€ Ãƒâ„¢Ã¢â‚¬Å¾ !ÃƒËœÃ‚Â¨ÃƒËœÃ‚Â§Ãƒâ„¢Ã¢â‚¬Å¾rateÃƒËœÃ‚Â§Ãƒâ„¢Ã¢â‚¬Å¾ÃƒËœÃ‚Â¬ÃƒËœÃ‚Â¯Ãƒâ„¢Ã…Â ÃƒËœÃ‚Â¯Ãƒâ„¢Ã¢â‚¬Â¡
        // DB::table('vote_choice')->insert($choice);
    }

    /**
     * @param $id
     * @param $desc
     */
    public static function updateChoiceDescription($id,$desc)
    {
        DB::table('vote_choice')
            ->where('id', $id)
            ->update(array(' description'=> $desc));
    }


    public static function updateChoiceRate($id,$data)
    {
        DB::table('vote_choice')
            ->where('id', $id)
            ->update($data);
    }


    /*
     * delete one choice only  by choice id
     * @param choiceId
     */
    public static function deleteOneChoiceByChoiceId($choiceId)
    {
        DB::table('vote_choice')->where('id', '=', $choiceId)->delete();

    }


    // Table: vote_user_choice
    /*------------------------------------------------------------*/


    /**
     * @param vote_user_choice
     */
    public static function AddUserInSpecificChoice($vote_user_choice)
    {
        DB::table('vote_user_choice')->insert($vote_user_choice);
    }




    /**
     * return all vote_user_choice_info  within specified choice Id
     * @param $choiceId
     */
    public static function getAllInfoByChoicId($choiceId)
    {
        $vote_user_choice_info = DB::table('vote_user_choice')->where('choice_id', '=', $choiceId)->get();
        return $vote_user_choice_info;
    }


    /**
     * return all vote_user_choice_info  within specified user Id
     * @param $userId
     */
    public static function getAllInfoByUserId($userId)
    {
        $vote_user_choice_info = DB::table('vote_user_choice')->where('user_id', '=', $userId)->get();
        return  $vote_user_choice_info;
    }


    /**
     * @param $choiceId
     * @return mixed
     */
    private static function getRateOfUsersForSpecificChoice($choiceId)
    {
        $usersCount= DB::table('vote_user_choice')
            ->groupBy('choice_id')
            ->having('choice_id', '=', $choiceId)->count('user_id');
        return $usersCount;


    }


    public static function getUserChoice($vote_id,$user_id){
        $data = DB::table('vote_choice')
            ->join('vote_user_choice','vote_user_choice.choice_id','=','vote_choice.id')
            ->where('vote_choice.vote_id','=',$vote_id)
            ->where('vote_user_choice.user_id','=',$user_id)
            ->select('vote_user_choice.choice_id')
            ->get();
        return $data;
    }


    /**
     * delet one choice bs specific user
     * @param $userId
     * @param $choiceId
     */
    public static function deleteOneChoiceByUser($userId,$choiceId)
    {
        DB::table('vote_user_choice')
            ->where('choice_id', '=' , $choiceId)->where('user_id', '=' , $userId)->delete();

    }

    public static function updateUserChoice($id,$data)
    {
        DB::table('vote_user_choice')
            ->where('choice_id', $id)
            ->update($data);
    }

    public static function getVoteGroup($user_id , $last_id){
        $results = DB::select('SELECT v.id,v.title,v.content,u.user_name,u.profile_picture,u.user_code,g.name,v.create_date FROM vote v left outer join users u on v.user_id = u.id left outer join groups g on v.group_id = g.id WHERE group_id in (select distinct group_id from group_members where user_id = :id and v.id <= :last_id) order by v.id desc LIMIT 5', ['id' => $user_id , 'last_id' => $last_id]);
        return $results;
    }

    public static function GetNewVote($last_id){
        $result=DB::table('vote')
            ->join('users','vote.user_id','=','users.id')
            ->join('groups','vote.group_id','=','groups.id')
            ->where('vote.id','>',$last_id)
            ->select('vote.id','vote.content','vote.title','users.user_name','groups.name as group_name')
            ->orderBy('vote.id','decs')
            ->get();
        return $result;
    }


    public static function countChosenvote($voteId)
    {
        $results = DB::select('SELECT COUNT(m.id) as vote_count , m.description FROM vote_choice AS M 
 JOIN vote AS V ON V.id = M.vote_id
JOIN vote_user_choice VU ON VU.choice_id = M.id
where v.id = ?
GROUP by m.id',array($voteId));
        return $results;
    }

}//end class Vote