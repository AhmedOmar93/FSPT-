<?php 

class GroupMember extends Eloquent{
	
	protected $table="group_members";
	
    public function details(){
    	return $this->hasOne('User','id','user_id');
    }






}