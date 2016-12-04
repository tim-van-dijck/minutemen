<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('events')->insert([
			[
				'title'				=> 'Event 1',
				'description'		=> 'Description 1',
				'max_teams'			=> 7,
				'starts_at'			=> date('Y-m-d H:i:s', strtotime('2016-12-10 13:00:00')),
				'ends_at'			=> date('Y-m-d H:i:s', strtotime('2016-12-10 18:00:00')),
				'street'			=> 'Helmstraat',
				'number'			=> '82',
				'zip'				=> '2140',
				'city'				=> 'Borgerhout',
				'coords'			=> '51.2173587;4.4313724',
				'banner'			=> null,
				'organisation_id'	=> 1
			],
			[
				'title'				=> 'Event 2',
				'description'		=> 'Description 2',
				'max_teams'			=> 7,
				'starts_at'			=> date('Y-m-d H:i:s', strtotime('2016-12-15 13:00:00')),
				'ends_at'			=> date('Y-m-d H:i:s', strtotime('2016-12-15 18:00:00')),
				'street'			=> 'Helmstraat',
				'number'			=> '82',
				'zip'				=> '2140',
				'city'				=> 'Borgerhout',
				'coords'			=> '51.2173587;4.4313724',
				'banner'			=> null,
				'organisation_id'	=> 1
			],
			[
				'title'				=> 'Event 3',
				'description'		=> 'Description 3',
				'max_teams'			=> 7,
				'starts_at'			=> date('Y-m-d H:i:s', strtotime('2016-12-20 13:00:00')),
				'ends_at'			=> date('Y-m-d H:i:s', strtotime('2016-12-20 18:00:00')),
				'street'			=> 'Helmstraat',
				'number'			=> '82',
				'zip'				=> '2140',
				'city'				=> 'Borgerhout',
				'coords'			=> '51.2173587;4.4313724',
				'banner'			=> null,
				'organisation_id'	=> 1
			]
		]);
	}
}
