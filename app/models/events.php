<?php 

class events extends Eloquent{
	
	protected $table="events";

	public function owner(){
		return $this->hasOne('User','id','userId');
	}

	public function group(){
		return $this->hasOne('Group','id','gId');
	}
	
}