<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('solution',function($solution){
			$solution->bigInteger('user_id')->unsigned();
			$solution->index('user_id');
			$solution->foreign('user_id')->references('id')->on('users')->onDelete('cascade');//code

			$solution->bigInteger('assignment_id')->unsigned();
			$solution->index('assignment_id');
			$solution->foreign('assignment_id')->references('id')->on('assignment')->onDelete('cascade');
			
			$solution->string('url');

			$solution->integer('grade');
			
			$solution->dateTime('upload_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('solution');
	}

}
