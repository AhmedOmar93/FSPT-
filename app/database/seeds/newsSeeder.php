<?php

class newsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $seed = [
            ['uid' => '1', 'title' => 'MIS'],
            ['uid' => '1', 'title' => 'DSS'],
            ['uid' => '3', 'title' => 'DSS'],
            ['uid' => '2', 'title' => 'HR']
        ];
        $db = DB::table('news')->insert($seed);

    }
}