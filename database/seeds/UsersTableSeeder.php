<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->insert([
			[
				'username'	=> 'admin',
				'slug'		=> 'admin',
				'firstname'	=> 'Admin',
				'lastname'	=> 'MacAdmin',
				'email'		=> 'admac@admin.com',
				'password'	=> bcrypt('admin123'),
				'img'		=> null,
				'kills'		=> 0,
				'deaths'	=> 0,
			],
			[
				'username'	=> 'timvandijck',
				'slug'		=> 'timvandijck',
				'firstname'	=> 'Tim',
				'lastname'	=> 'van Dijck',
				'email'		=> 'tim@vandijck.com',
				'password'	=> bcrypt('t1mp1312'),
				'img'		=> null,
				'kills'		=> 0,
				'deaths'	=> 0,
			],
			[
				'username'	=> 'user3',
				'slug'		=> 'user3',
				'firstname'	=> 'user',
				'lastname'	=> '3',
				'email'		=> 'u3@user.com',
				'password'	=> bcrypt('t1mp1312'),
				'img'		=> null,
				'kills'		=> 0,
				'deaths'	=> 0,
			]
		]);
	}
}
