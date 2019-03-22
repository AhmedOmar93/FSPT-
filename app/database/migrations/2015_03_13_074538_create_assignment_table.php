<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentTable extends Migration {

	public function up()
	{
		Schema::create('assignment',function($assignment){
			$assignment->bigIncrements('id');

			$assignment->bigInteger('user_id')->unsigned();
			$assignment->index('user_id');
			$assignment->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			$assignment->bigInteger('group_id')->unsigned();
			$assignment->index('group_id');
			$assignment->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

			$assignment->string('title');

			$assignment->string('url');

			$assignment->dateTime('upload_date');

			$assignment->dateTime('due_date');
		});
	}

	public function down()
	{
		Schema::drop('assignment');
	}

}
