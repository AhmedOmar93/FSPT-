<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 12/02/2015
 * Time: 08:18 م
 */

class Users {




    /**
     * this function to return all users in the systems .
     * @return mixed
     */
    public static function getAllUsers()
    {
        $users = DB::table('users')->get();
        return $users;
    }

/*
 * check if user exist or not !
 */
    public static function signIn($userCode,$password,$active)
    {
        $user = DB::table('users')->where('user_code',$userCode)->where('active',$active)->where('password',$password)->count();
        return $user;
    }


    public static function getUserByEmail($email)
    {
        $users = DB::table('users')->where('email', '=', $email)->first();
        return $users;
    }

    public static function getUserByUserCode($code)
    {
        $user = DB::table('users')->where('user_code', '=', $code)->first();
        return $user;
    }
    /**
     * this function to return all students by a given level
     * @param $level
     * @return mixed
     */
    public static function getAllUsersByLevel($level)
    {
        $users = DB::table('users')->where('level', '=', $level)->get();
        return $users;
    }

    /**
     * this methods to return number of students in specific level
     * @param $level
     */
    public static function getCountOfStudentByLevel($level)
    {
        $stNumber = DB::table('users')->where('level', '=', $level)->count();
        return $stNumber;
    }

    /**
     * this function to return users in specific department
     * @param $department
     * @return mixed
     */
    public static function getAllUsersByDepartment($department)
    {
        $users = DB::table('users')->where('department', '=', $department)->get();
        return $users;
    }

    /**
     * this function to return users in specific profession
     * @param $prof
     * @return mixed
     */
    public static function getAllUsersByProfession($prof)
    {
        $users = DB::table('users')->where('profession', '=', $prof)->get();
        return $users;
    }

    /**
     * this function to return specific user by using active code to sign in  .
     * @param $code
     * @return mixed
     */
    public static function getUserByActivationCode($code,$active)
    {
        $user = DB::table('users')->where('active_code',$code)->where('active',$active)->first();
        return $user;
    }

    public static function getUserByCodeToRecover($code)
    {
        $user = DB::table('users')->where('active_code',$code)->where('password_temp','!=','')->first();
        return $user;
    }

    /**
     * get profile picture for specific user .
     * @param $code
     * @return mixed
     */
    public static function getUserProfilePicture($code)
    {
        $image = DB::table('users')->where('user_code',$code)->pluck('profile_picture');;
        return $image;
    }



    /**
     * get number of users in specific profession
     * @param $profession
     * @return mixed
     */
    public static function getCountOfUsersByProfession($profession)
    {
        $usersNumbers = DB::table('users')->where('profession', '=', $profession)->count();
        return $usersNumbers;
    }

    /**
     * add new user .
     * @param $user
     */
    public static function create($user)
    {
        if( DB::table('users')->insert($user))
        return true;
        else
        return false;
    }

    /**
     * add new user and get new id .
     * @param $user
     * @return mixed
     */
    public static function createAndGetId($user)
    {
        $id = DB::table('users')->insertGetId($user);
        return $id;
    }

    /**
     * update data for specific user .
     * @param $code
     * @param $data
     */
    public static function update($id,$data)
    {
        if(DB::table('users')->where('id', $id)->update($data))
            return true;
        else
            return false;

    }

    /**
     * delete one user .
     * @param $code
     */
    public static function deleteOneUser($code)
    {
        DB::table('users')->where('user_code', '=', $code)->delete();
    }

    /**
     *delete all student .
     */
    public static function deleteAllStudent()
    {
        DB::table('users')->where('level', '!=', NULL)->delete();
    }

    /**
     * delete student in specific level.
     * @param $level
     */
    public static function deleteStudentByLevel($level)
    {
        DB::table('users')->where('level', '=',$level)->delete();
    }


    /**
    *crete Connection and Store Information 
    *@param $userConn 
    */
    public static function createConn($userConn)
    {
        $result = DB::table('connection_info')->insert($userConn);
        
        if($result)
            return "true";
        else
            return "false";
         
    }

    public static function getConnInfo(){
        $conn = DB::table('connection_info')->where('users', '=', Auth::user()->id)->get();
        return $conn;
    }

    public static function getUserByConnId($connId,$conn_sess)
    {
        
        $user = DB::table('connection_info')->where('connId','=',$connId)
        ->where('Fonline','=',1)
        ->where('conn_sess','=',$conn_sess)
        ->select('users','id')->first();
        return $user;
    }

    public static function CloseConnection($id)
    {
        DB::table('connection_info')
            ->where('id', $id)
            ->where('Fonline', 1)
            ->delete();
    }

    public static function getOnlineUsers( )
    {
         $user_id = Auth::user()->id;
          $results = DB::select('SELECT USERS FROM CONNECTION_INFO 
WHERE USERS  IN(
SELECT if(user1=?,user2,user1) AS USERS 
            FROM (SELECT `user1_id` AS user1, `user2_id` AS user2 
            FROM `friends_list` AS M
            inner join users AS U1 on U1.id = M.`user1_id`
            inner join users AS U2 on U2.id = M.`user2_id`
            inner join `chat_roome` AS C on C.id = M.`room_id`
            where M.`user1_id` = ?  or M.`user2_id` = ?
                 ) AS sb
    ) AND FONLINE = 1',array($user_id,$user_id,$user_id)) ;
        return $results;
    }

    public static function checkCode($code){
        $data = DB::table('users')->where('user_code', '=', $code)->first();
        return $data;
    }

    public static function checkEmail($email){
        $data = DB::table('users')->where('email', '=', $email)->first();
        return $data;
    }

}