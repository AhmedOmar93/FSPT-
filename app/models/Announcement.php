<?php


class Announcement
{
	/*
	|
	| Announcements 
	| announcement Table
	|
	*/
	/*	Insert single announcement in the announcement table
	Parameters :
	$announcement : Takes the following info
	content , user id posting the announcement, creation date , deadline , title */
	public static function InsertAnnouncement ($announcement)
	{
		$result = DB::table('announcement')->insertGetId( $announcement);
		return $result;
	}
	/* Updates single announcement in the announcement Table
	Parameters :
	$announcementId .
	$announcementUpdates : Takes the info to be updated*/
	public static function UpdateAnnouncement ($announcementId , $announcementUpdates)
	{
		DB::table('announcement')
            ->where('id', $announcementId)
            ->update($announcementUpdates);
	}
	/*  Deletes single announcement from the announcement Table
	Parameters :
	$announcementId : Takes the announcement Id*/
	public static function DeleteAnnouncement ($announcementId)
	{
		DB::table('announcement')->where('id', '=', $announcementId)->delete();
	}
	/*  Select announcement for a given column 
	Parameters :
	$announcementColumn :for a certain date , user id , title and so on
	$operator : = , > etc
	$announcementValue : value for example 2/2/2014 , 20112222 , midterms 
	$last_id : value for example 9 , 10 , 5
	*/
	public static function SelectAnnouncementBy ($announcementColumn , $operator ,$announcementValue , $last_id)
	{
		$announcementlist =  DB::table('announcement')
			->where($announcementColumn, $operator , $announcementValue)
			->where('announcement.id' , '<=' , $last_id)
			->orderBy('announcement.id' , 'desc')
			->take(5)
			->get();
		return $announcementlist;
	}

	/*
	|
	| Announcements For Users
	| announcement_user Table
	|
	*/

	/*	Insert Users in the announcement to Announcement_Users table
	Parameters :
	$users : Takes the following info
	user id and the announcement id */
	public static function InsertAnnouncementUser ($announcementUser)
	{
		DB::table('announcement_user')->insert( $announcementUser);
	}
	/* Updates Users involved in the announcement in the announcement_user Table
	Parameters :
	$groupId .
	$announcementId
	$updates : Takes the info to be updated*/
	public static function UpdateAnnouncementUser ($userId ,$announcementId ,$updates)
	{
		DB::table('announcement_user')
            ->where('user_id', $userId)
            ->where('announcement_id', $announcementId)
            ->update($updates);
	}
	/*  Deletes a user involved in the announcement from the announcement_user Table
	Parameters :
	$userId .
	$announcementId : Takes the announcement Id*/
	public static function DeleteAnnouncementUser ($userId ,$announcementId)
	{
		DB::table('announcement_user')
			->where('user_id', '=', $userId)
			->where('announcement_id', '=', $announcementId)
			->delete();
	}
	/*  Select user involved in an announcement 
		Select the announcements  of certain user	
	Parameters :
	$column :for a certain user id or announcement_id
	$operator : = , > etc
	$value : value for example 20110144 , 2 */
	public static function SelectAnnouncementOrUser ($column , $operator ,$value)
	{
		$AnnouncementOrUserList =  DB::table('announcement_user')
			->where($column, $operator , $value)
			->orderBy('announcement_id','desc')
			->get();
		return $AnnouncementOrUserList;
	}

	/*
	|
	| Announcements For Groups
	| announcement_group Table
	|
	*/
	/*	Insert Groups in the announcement to Announcement_Group table
	Parameters :
	$group : Takes the following info
	group id and the announcement id */
	public static function InsertAnnouncementGroup ($announcementGroup)
	{
		DB::table('announcement_group')->insert( $announcementGroup);
	}
	/* Updates groups involved in the announcement in the Announcement_Group Table
	Parameters :
	$groupId .
	$announcementId
	$updates : Takes the info to be updated*/
	public static function UpdateAnnouncementGroup ($groupId ,$announcementId ,$updates)
	{
		DB::table('announcement_group')
            ->where('group_id', $groupId)
            ->where('announcement_id', $announcementId)
            ->update($updates);
	}
	/*  Deletes a group involved in the announcement from the Announcement_Group Table
	Parameters :
	$groupId :
	$announcementId : Takes the announcement Id*/
	public static function DeleteAnnouncementGroup ($groupId ,$announcementId)
	{
		DB::table('announcement_group')
			->where('group_id', '=', $groupId)
			->where('announcement_id', '=', $announcementId)
			->delete();
	}
	/*  Select group involved in an announcement 
		Select the announcements  of certain groups	
	Parameters :
	$column :for a certain group id or announcement_id
	$operator : = , > etc
	$value : value for example 24 , 2 */
	public static function SelectAnnouncementOrGroup ($column , $operator ,$value)
	{
		$AnnouncementOrGroupList =  DB::table('announcement_group')->where($column, $operator , $value)->get();
		return $AnnouncementOrGroupList;
	}
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public static function getAnnouncementGroup($user_id , $announcement_id)
	{
		$announcement_ids= DB::select('select u.user_name,u.profile_picture,a.create_date,a.title,a.content,g.name as group_name, u.user_code , a.id, a.user_id as checks , a.like from group_members gm left outer join announcement_group ag on gm.group_id = ag.group_id left outer join announcement a on a.id = ag.announcement_id left outer join groups g on ag.group_id = g.id left outer join users u on u.id = a.user_id where gm.user_id = :user_id and a.id <= :last_id order by a.id desc limit 5'  , ['last_id' => $announcement_id , 'user_id' => $user_id]);
		return $announcement_ids;
	}
	
	public static function getAnnouncementLevel($user_level , $announcement_id)
	{
		$result=DB::table('announcement_user')
			->join('announcement','announcement.id','=','announcement_user.announcement_id')
			->join('users' , 'announcement.user_id' , '=' , 'users.id')
			->where('users.level','=',$user_level)
			->where('announcement.id' , '<=' , $announcement_id)
			->select('users.user_name','users.profile_picture','announcement.create_date','announcement.title','announcement.content', 'users.user_code' , 'announcement.id' , 'users.level' , 'announcement.user_id as check' , 'announcement.like')
			->distinct()
			->take(5)	
			->get();

		return $result;

	}
	public static function GetNewAnnouncement($last_id)
	{
		$result=DB::table('announcement')
			->join('users','announcement.user_id','=','users.id')
			->join('announcement_group','announcement.id','=','announcement_group.announcement_id')
			->join('groups','announcement_group.group_id','=','groups.id')
			->where('announcement.id','>',$last_id)
			->select('users.user_name','announcement.title','announcement.content','groups.name as group_name','announcement.id')
			->orderBy('announcement.id','desc')
			->get();
		return $result;
	}
	public static function GetMaxId(){

		$result=DB::table('announcement')->max('announcement.id');
		return $result;
	}
	public static function GetGroupAnnouncement($announcement_id,$group_id){
		$result=DB::table('announcement')
			->join('users','announcement.user_id','=','users.id')
			->join('announcement_group','announcement.id','=','announcement_group.announcement_id')
			->join('groups','announcement_group.group_id','=','groups.id')
			->where('announcement.id','<=',$announcement_id)
			->where('groups.id','=',$group_id)
			->select('users.user_name','users.profile_picture','users.user_code','announcement.create_date','announcement.title','announcement.content','groups.name as destination','announcement.id','announcement.user_id as check')
			->orderBy('announcement.id','desc')
			->take(5)
			->get();
		return $result;
	
	}

	public static function AddComment($user_id,$announcement_id,$content){

		DB::table('comment_answer')->insert(
			array('content' => $content, 'announcement_id' => $announcement_id, 'user_id' => $user_id)
			);
	}

	public static function GetAnnouncementComment($announcement_id){

		$result = DB::table('comment_answer')
			->join('users' , 'comment_answer.user_id' , '=' , 'users.id')
			->where('comment_answer.announcement_id' , '=' , $announcement_id)
			->select('users.user_name','users.profile_picture','users.user_code','comment_answer.content') 
			->get();
		return $result;
	}


	public static function insertLike($announcement_id , $user_id){
		$result = DB::table('announcement_like')->insert(array('announcement_id' => $announcement_id , 'user_id' => $user_id));
		return $result;
	}

	public static function update_like($announcement_id){
		//UPDATE announcement SET announcement.like=announcement.like+1 WHERE id=$announcement_id
		DB::update('UPDATE announcement a SET a.like = a.like + 1 WHERE a.id = :announcement_id' , ['announcement_id' => $announcement_id ]);
	}

	public static function getLevelAnnouncement($user_id , $last_id){
		$result = DB::table('announcement_user')
			->join('announcement' , 'announcement_user.announcement_id' , '=' , 'announcement.id')
			->join('users' , 'announcement.user_id' , '=' , 'users.id')
			->where('announcement_user.user_id' , '=' , $user_id)
			->where('announcement.id' , '<=' , $last_id)
			->orderBy('announcement.id' , 'desc')
			->take(5)
			->get();
		return $result;
	}

	public static function getAllChichat($last_id){
		$result = DB::select('select a.content,a.id as announcement_id,a.like,a.create_date,u.user_code,u.user_name,u.profile_picture from Announcement a left outer join users u on a.user_id = u.id where a.id not in(select distinct announcement_id from announcement_group) and a.id not in (select distinct announcement_id from announcement_user) and a.id <= :last_id order by a.id desc limit 5' , ['last_id' => $last_id]);
		return $result;	
	}

	public static function getAllChichatUser($last_id,$user_id){
		$result = DB::select('select a.content,a.id as announcement_id,a.like,a.create_date,u.user_code,u.user_name,u.profile_picture from Announcement a left outer join users u on a.user_id = u.id where a.id not in(select distinct announcement_id from announcement_group) and a.id not in (select distinct announcement_id from announcement_user) and a.id <= :last_id and a.user_id = :user_id order by a.id desc limit 5' , ['last_id' => $last_id , 'user_id' => $user_id]);
		return $result;	
	}
}