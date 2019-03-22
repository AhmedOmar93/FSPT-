<?php 

class Notification
{


public static function InsertNotification ($notify){
  DB::table('notification')->insert($notify);
  return true ;
 }


 public static function UpdateNotifications ($userId){
  DB::table('notification_seen')
    ->where('users', $userId)
    ->update(array('seen' => 1));
    return true ;
 }


 public static function DeleteNotification ($notifyId , $userId){
  DB::table('notification')
    ->where('users', $userId)
    ->where('id', $notifyId)->delete();
    return true ;
 }
 
 public static function get_notification ($user_id){
  $List = DB::table('notification_seen')
    ->select('notification.message','notification.creation_date')
    ->where('notification_seen.users', '=', $user_id)
    ->join('notification','notification_seen.notification','=','notification.id')
   // ->where('seen', '=',0)
    ->orderBy('notification.creation_date', 'DESC')
    ->get();
  return $List ;
 }



 public static function get_notification_count ($user_id){
  $List = DB::table('notification_seen')
    ->select(DB::raw('count(-1) as notification_count'))
    ->where('notification_seen.users', '=', $user_id)
    ->join('notification','notification_seen.notification','=','notification.id')
    ->where('seen', '=',0)
    ->get();
  return $List ;
 }
}