<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 06/03/2015
 * Time: 11:18 م
 */

class ChatController extends BaseController {
    /*
     * this method to return all chat rooms for specific user
     */
    public function getAllChatRoom($user_id){
        $data = Chat::getAllChatRoom($user_id);
        return $data;
    }

    /*
     * this method to return chat room users.
     */
    public function getChatRoomUsers($room_id){
        $data = Chat::getChatRoomUsers($room_id);
        return $data;
    }

    /*
     * this method to return chat room messages between users .
     */
    public function getChatRoomMessages($room_id){
        $data = Chat::getChatRoomMessages($room_id);
        return $data;
    }

    public function setNewRoom(){
       // Response::json(Input::all());
        $data = array(
            'name'=>Response::json(Input::get('name')),
            'created_at'=>Carbon\Carbon::now()->toDateTimeString(),
            'updated_at'=>Carbon\Carbon::now()->toDateTimeString()
        );

        $id = Chat::setNewChatRoomAndGetId($data);
        for($i=0;$i<Response::json(Input::get('count'));$i++){
            $data = array(
                'user_id'=>Response::json(Input::get('count'))+$i,
                'chat_room_id'=>$id
            );
            Chat::setChatRoomUsers($data);
        }
    }

     public function setNewMessage( ){
        $data = array(
            'body'=>Input::get('msg'),
            'user_id'=>Input::get('user_id'),
            'chat_room_id'=>Input::get('room_id'),
            'created_at'=>Carbon\Carbon::now()->toDateTimeString(),
            'updated_at'=>Carbon\Carbon::now()->toDateTimeString()
        );
        Chat::setNewMessage($data);
    }

    

    public function getAllFrindsMsg( )
    {
         $data = FriendsList::getAllFriends(Auth::user()->id,1);
         //var_dump($data);
        // die();
         $arrData = [];
           foreach ($data as $key => $value) {        
            foreach ($value as $key => $value) {
                if($key == "room_id")
                $arrData =   Chat::getLastMessageRoom($value) ;
                   
                }                
        }
         return $arrData;
    }

    public function getDateFormate($date)
    {
        
        $today = new DateTime(); // This object represents current date/time
        $today->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

        $match_date = DateTime::createFromFormat( "Y-m-d H:i:s",$date);
        $match_date->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

        $diff = $today->diff( $match_date );
        $diffDays = (integer)$diff->format( "%R%a" );

        if ($diffDays == 0) {
            $test = new DateTime($date);
            return  date_format($test, 'H:i a');
        }
        else{
            $test = new DateTime($date);
            return  date_format($test, 'M jS, Y');
        }
  
    }


}