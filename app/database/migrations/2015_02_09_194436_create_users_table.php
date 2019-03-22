<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('layout',function($users){
			$users->bigIncrements('id');
			$users->string('code',20);
			$users->string('first_name',50);
			$users->string('middle_name',50);
			$users->string('last_name',50);
			$users->string('user_name',100);
			$users->string('password'); //default 255
			$users->string('email',100);
			$users->dateTime('create_date');
			$users->dateTime('update_date');
			$users->date('birth_date');
			$users->string('street',100)->nullable();
			$users->string('city',100)->nullable();
			$users->string('country',100)->nullable();
			$users->string('profile_picture')->nullable();
			$users->enum('gender',array('male','female'));
			$users->bigInteger('phone')->nullable()->unsigned();
			$users->string('profession');
			$users->string('department',100);
			$users->enum('level',array(1,2,3,4));
			$users->unique('code');
			$users->unique('email');
			$users->unique('phone');

		});

		/*
         * 		Schema::create('group_members',function($member){
                    $member->bigIncrements('id');
                    $member->bigInteger('group_id');
                    $member->index('group_id');
                    $member->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
                    $member->bigInteger('user_id');
                    $member->index('user_id');
                    $member->foreign('user_id')->references('id')->on('layout')->onDelete('cascade');

                });
         */
	}
	/*
     * Schema::create('groups',function($group){
                $group->bigIncrements('id');
                $group->string('name');
                $group->longText('description');
                $group->string('image')->nullable();
                $group->date('create_date');
                $group->bigInteger('user_id')->unsigned();
                $group->index('user_id');
                $group->foreign('user_id')->references('id')->on('layout')->onDelete('cascade');
            });
     */
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('layout');
	}

}
