<?php
class NotificationController extends BaseController {


 public  function get_notification ( ){
 	
 	$results = Notification::get_notification(Auth::user()->id );
 	return $results ;
 }


 public  function get_notification_count ( ){
 	
 	$results = Notification::get_notification_count(Auth::user()->id );
 	return $results ;
 }




 public  function UpdateNotifications ( ){
 	try{
 		$results = Notification::UpdateNotifications(Auth::user()->id );
  	}
  	catch(Exception  $e){
  		 echo 'Caught exception: ',  $e->getMessage(), "\n";
  	}
  }



}
?>


