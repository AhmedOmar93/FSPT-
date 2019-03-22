<?php 

class comments extends Eloquent{
	
	protected $table="comment_answer";
	public  function owner(){
        return $this->hasOne('User','id','user_id');
    }	
}