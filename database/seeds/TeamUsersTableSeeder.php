<?php

use Illuminate\Database\Seeder;

class TeamUsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('team_users')->insert([
			[
				'team_id'		=> 1,
				'user_id'		=> 1,
				'admin'			=> 1,
				'pending'		=> 0,
				'invite'		=> 0,
				'created_at'	=> '2015-07-30 17:33:24',
				'deleted_at'	=> '2016-04-22 15:03:20'
			],
			[
				'team_id'		=> 1,
				'user_id'		=> 2,
				'admin'			=> 0,
				'pending'		=> 0,
				'invite'		=> 0,
				'created_at'	=> '2015-08-07 12:30:24',
				'deleted_at'	=> null,
			]
		]);
	}
}
