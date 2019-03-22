<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 03/04/2015
 * Time: 08:57 م
 */

class FriendsList {

    public static function addFriendsList($data){
        return DB::table('friends_list')->insertGetId($data);
    }

    public static function activeFriendsList($id){
        DB::table('friends_list')
            ->where('id', $id)
            ->update(array('active' => 1));
    }

    public static function getFriendsRequest($user2_id){
        $data = DB::table('friends_list')
            ->join('users','users.id','=','friends_list.user1_id')
            ->where('friends_list.user2_id','=',$user2_id)
            ->select('friends_list.id as friendsListId','users.id as usersId','users.first_name','users.last_name','users.profile_picture')
            ->get();
        return $data;
    }

    public static function getAllFriends($user_id,$status){
        $results = DB::select('SELECT if(user1=?,user2,user1) AS user_id , if(user1=?,user2_first_name,user1_first_name) AS first_name ,
            if(user1=?,user2_last_name,user1_last_name) AS last_name , friendsListId , room_id,
            if(user1=?,user2_profile_picture,user1_profile_picture) AS profile_picture 
            FROM (SELECT `user1_id` AS user1, `user2_id` AS user2 , M.`id` AS friendsListId , 
            U1.`first_name` AS user1_first_name , U1.`last_name` AS user1_last_name , U1.`profile_picture` AS user1_profile_picture ,
            U2.`first_name` AS user2_first_name , U2.`last_name` AS user2_last_name , U2.`profile_picture` AS user2_profile_picture,
            C.`id` AS room_id
            FROM `friends_list` AS M
            inner join users AS U1 on U1.id = M.`user1_id`
            inner join users AS U2 on U2.id = M.`user2_id`
            inner join `chat_roome` AS C on C.id = M.`room_id`
            where ( M.`user1_id` = ?  or M.`user2_id` = ? ) and M.`active` = ?
                 ) AS sb',array($user_id,$user_id,$user_id,$user_id,$user_id,$user_id,$status));
            return $results;
  
    }

    public static function checkFriends($user1,$user2){
        $data = DB::table('friends_list')
            ->where('user1_id','=',$user1)
            ->where('user2_id','=',$user2)
            ->orwhere('user2_id','=',$user1)
            ->where('user1_id','=',$user2)
            ->first();
        return $data;

    }

    public static function cancelRequest($id){
        DB::table('friends_list')->where('id', '=',$id)->delete();
    }




}