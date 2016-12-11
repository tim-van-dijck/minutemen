<?php

use Illuminate\Database\Seeder;

class ParticipationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('participations')->insert([
			[
				'event_id'		=> 1,
				'team_id'		=> 1
			],
			[
				'event_id'		=> 1,
				'team_id'		=> 2
			],
			[
				'event_id'		=> 1,
				'team_id'		=> 3
			],
			[
				'event_id'		=> 1,
				'team_id'		=> 4
			],
			[
				'event_id'		=> 1,
				'team_id'		=> 5
			],
			[
				'event_id'		=> 1,
				'team_id'		=> 6
			],
			[
				'event_id'		=> 1,
				'team_id'		=> 7
			],
			[
				'event_id'		=> 1,
				'team_id'		=> 8
			],
			[
				'event_id'		=> 1,
				'team_id'		=> 9
			],
			[
				'event_id'		=> 1,
				'team_id'		=> 10
			],
		]);
    }
}
