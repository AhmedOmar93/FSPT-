<?php 

class Task extends Eloquent{
	
	protected $table="task";

	public function group_tasks(){
		$obj=$this->belongsTo('Group','group_id','id');
		return $obj;
	}
	
}