<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 25/02/2015
 * Time: 02:41 م
 */
class ProfileController extends BaseController{
    
    private static $image_path="../../../../images/";

    public function user($userCode)
    {
        $checkFriends = '';

        $user = Users::getUserByUserCode($userCode);
        $groups = Group::getAllGroupsByUserId($user->id);
       // $friend = FriendsList::getAllFriends($user->id);
        if($user->id != Auth::user()->id) {
            $checkFriends = FriendsList::checkFriends($user->id,Auth::user()->id);
            
        }else{
            $checkFriends = NULL;
        }
            return View::make('profile.user')
                ->with('user',$user)
                ->with('checkFriends',$checkFriends)
        ->with('groups',$groups);
    }


    public static function image_path(){
        return ProfileController::$image_path;
    }

    public static function get_PP(){
            return ProfileController::$image_path.Auth::user()->profile_picture;        
    }

    public static function get_name(){
        return Auth::user()->first_name." ".Auth::user()->last_name;
    }

    public static function get_questions(){
        return QuestionController::get_user_questions(Auth::user()->id);
    }


    public static function get_groups(){

        $data = Group::getAllGroupsByUserId(Auth::user()->id);
        //print_r($data);
        return $data;
    }

    public static function get_Technical_groups(){

        $data = Group::getAllGroupsTechnical(Auth::user()->id);
        //print_r($data);
        return $data;
    }

    public static function checkMemberInGroup($groupId){
        $data = Group::checkMemberInGroup(Auth::user()->id,$groupId);
        return $data;
    }

    public static function get_theme(){
        $themeName="";
        switch(Auth::user()->color){
        case "Green":
            $themeName="skin-green";
            break;
        case "Red":
            $themeName="skin-red";
            break;
        case "Blue":
            $themeName="skin-blue";
            break;
        case "Purple":
            $themeName="skin-purple";
            break;
        case "Yellow":
            $themeName="skin-yellow";
            break;
        case "Black":
            $themeName="skin-black";
            break;
        
        default:
            $themeName="skin-blue";
        }
        
        return $themeName;
    }

    public static function get_class(){
        $class="";
        switch(Auth::user()->color){
        case "Green":
            $class="success";
            break;
        case "Red":
            $class='danger';
            break;
        case "Blue":
            $class='primary';
            break;
        case "Purple":
            $class="default";
            break;
        case "Yellow":
            $class='warning';
            break;
        case "Black":
            $class="default";
            break;
        
        default:
    }
    return $class;
    }
}