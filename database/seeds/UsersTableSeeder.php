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
				'street'	=> '',
				'number'	=> null,
				'zip'		=> '',
				'city'		=> '',
				'lat'	    => 51.2173587,
				'long'      => 4.4313724,
				'password'	=> bcrypt('admin123'),
				'img'		=> null,
				'lfg'		=> 0,
				'admin'		=> 1,
			],
			[
				'username'	=> 'timvandijck',
				'slug'		=> 'timvandijck',
				'firstname'	=> 'Tim',
				'lastname'	=> 'van Dijck',
				'email'		=> 'tim@vandijck.com',
				'street'	=> '',
				'number'	=> null,
				'zip'		=> '',
				'city'		=> '',
                'lat'	    => 51.2173587,
                'long'      => 4.4313724,
				'password'	=> bcrypt('t1mp1312'),
				'img'		=> null,
				'lfg'		=> 0,
				'admin'		=> 0,
			],
			[
				'username'	=> 'user3',
				'slug'		=> 'user3',
				'firstname'	=> 'user',
				'lastname'	=> '3',
				'email'		=> 'u3@user.com',
				'street'	=> '',
				'number'	=> null,
				'zip'		=> '',
				'city'		=> '',
                'lat'	    => 51.2173587,
                'long'      => 4.4313724,
				'password'	=> bcrypt('t1mp1312'),
				'img'		=> null,
				'lfg'		=> 0,
				'admin'		=> 0,
			],
			[
				'username'	=> 'evert',
				'slug'		=> 'evert',
				'firstname'	=> 'Evert',
				'lastname'	=> 'sbaard',
				'email'		=> 'evert@evertsbaard.be',
				'street'	=> '',
				'number'	=> null,
				'zip'		=> '',
				'city'		=> '',
                'lat'	    => null,
                'long'      => null,
				'password'	=> bcrypt('evertsbaard'),
				'img'		=> null,
				'lfg'		=> 0,
				'admin'		=> 0,
			]
		]);
	}
}
