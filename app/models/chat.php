<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 06/03/2015
 * Time: 11:19 م
 */

class Chat {

    public static function getAllChatRoom($user_id){

        $data = DB::table('chat_room_users')
            ->join('users','users.id','=','chat_room_users.user_id')
            ->join('chat_roome','chat_roome.id','=','chat_room_users.chat_room_id')
            ->where('users.id','=',$user_id)
            ->select('chat_roome.name','chat_roome.created_at','chat_roome.updated_at')
            ->get();

        return $data;

    }

    public static function getChatRoomUsers($chat_room_id){
        $data = DB::table('chat_room_users')
            ->join('users','users.id','=','chat_room_users.user_id')
            ->join('chat_roome','chat_roome.id','=','chat_room_users.chat_room_id')
            ->where('chat_roome.id','=',$chat_room_id)
            ->select('chat_roome.name','chat_roome.id as room_id','chat_roome.created_at',
                'users.user_name','users.id','users.first_name','users.last_name')
            ->get();

        return $data;
    }




    public static function getChatRoomMessages($chat_room_id){
        $data = DB::table('messages')
            ->join('users','users.id','=','messages.user_id')
            ->join('chat_roome','chat_roome.id','=','messages.chat_room_id')
            ->where('chat_roome.id','=',$chat_room_id)
            ->select('messages.body','messages.created_at','users.user_name','users.id',
                'users.first_name','users.last_name','messages.id as msgId','users.profile_picture as pic')
            ->orderBy('messages.created_at')
            ->get();

        return $data;
    }



    public static function getLastMessageRoom($chat_room_id){
        $data = DB::table('messages')
            ->join('users','users.id','=','messages.user_id')
            ->join('chat_roome','chat_roome.id','=','messages.chat_room_id')
            ->where('chat_roome.id','=',$chat_room_id)
            ->where('messages.user_id','<>',Auth::user()->id)
            ->select('messages.body','messages.created_at','users.user_name','users.id',
                'users.first_name','users.last_name','messages.id as msgId','users.profile_picture as pic')
            ->orderBy('messages.created_at','DESC')
            ->take(1)
            ->get();

        return $data;
    }


    public static function setNewChatRoomAndGetId($data){
       $id =  DB::table('chat_roome')->insertGetId($data);
        return $id;
    }

    public static function setChatRoomUsers($data){
        DB::table('Table: chat_room_users')->insert($data);
    }

    public static function setNewMessage($data)
    {       
        DB::table('messages')->insertGetId($data);
    }

    public static function deleteMessage($id){
        DB::table('messages')->where('id', '=',$id)->delete();
    }

}