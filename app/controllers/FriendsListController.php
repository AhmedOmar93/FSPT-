<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 03/04/2015
 * Time: 09:01 م
 */

class FriendsListController extends BaseController{

    public function addFriends($user2){
        $room = array(
            'created_at'=>Carbon\Carbon::now()->toDateTimeString(),
            'updated_at'=>Carbon\Carbon::now()->toDateTimeString(),
            'private'=>1
        );
        
        $friendsRequest = array(
            'active'=>0,
            'created_at'=>Carbon\Carbon::now()->toDateTimeString(),
            'updated_at'=>Carbon\Carbon::now()->toDateTimeString(),
            'user1_id'=>Auth::user()->id,
            'user2_id'=>$user2,
            'room_id'=>Chat::setNewChatRoomAndGetId($room)
        );
        $id = FriendsList::addFriendsList($friendsRequest);
        $response=array(
            'state' =>'success In Add Friend.',
            'id' =>$id 
        );
        return Response::json($response);
    }

    public function activeFriends($id){
        FriendsList::activeFriendsList($id);
    }

     public function getFriendsRequest(){
       $data = FriendsList::getAllFriends(Auth::user()->id,0);
        $arrData = [];
        foreach ($data as $key => $value) {
            $tempData = [];
            foreach ($value as $key => $value) {
                    $tempData[$key] = $value;          
                } 
                $arrData[]= $tempData;        
        }
        //$data = json_encode($arrData);
        return $arrData;
    }

    public function getAllFriends(){
        $data = FriendsList::getAllFriends(Auth::user()->id,1);
        $arrData = [];
        foreach ($data as $key => $value) {
            $tempData = [];
            foreach ($value as $key => $value) {
                    $tempData[$key] = $value;          
                } 
                $arrData[]= $tempData;        
        }
        $data = json_encode($arrData);
        return $data;
    }

    public function cancelRequest($id){
           FriendsList::cancelRequest($id);
        $response=array(
            'state' =>'success in cancel request',
        );
        return Response::json($response);
    }


}