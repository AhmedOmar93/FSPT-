<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('newsSeeder');
		 $this->call('usersSeeder');
		 $this->call('questionsSeeder');
		 $this->call('answersSeeder');
		 $this->call('commentsSeeder');
		 
		

	}

}
