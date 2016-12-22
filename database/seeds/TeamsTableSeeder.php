<?php

use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('teams')->insert([
			[
				'name'			=> 'Rake Poepklappers',
				'slug'			=> 'RakePoepklappers',
				'tag'			=> 'POEP',
				'description'	=> 'De Poepklappers die zowaar steeds raak klappen!',
				'emblem'		=> null
			],
			[
				'name'			=> 'Team Supertof',
				'slug'			=> 'TeamSupertof',
				'tag'			=> 'STOF',
				'description'	=> 'Het supertofste team!!',
				'emblem'		=> null
			],
			[
				'name'			=> 'De Wally\'s',
				'slug'			=> 'DeWallys',
				'tag'			=> 'WAUW',
				'description'	=> '<p>Ik spring uit een vliegmasjien</p>',
				'emblem'		=> null
			],
			[
				'name'			=> 'Team All Star',
				'slug'			=> 'TeamAllStar',
				'tag'			=> 'GOLD',
				'description'	=> '<p>Somebody once told me<br/>the world was gonna roll me</p><p>I ain\'t the sharpest tool in the shed</p>',
				'emblem'		=> null
			],
                [
				'name'			=> 'Team 1',
				'slug'			=> 'Team1',
				'tag'			=> 'T1',
				'description'	=> 'blah',
				'emblem'		=> null
			],
			[
				'name'			=> 'Team 2',
				'slug'			=> 'Team2',
				'tag'			=> 'T2',
				'description'	=> 'blah',
				'emblem'		=> null
			],
			[
				'name'			=> 'Team 3',
				'slug'			=> 'Team3',
				'tag'			=> 'T3',
				'description'	=> 'blah',
				'emblem'		=> null
			],
			[
				'name'			=> 'Team 4',
				'slug'			=> 'Team4',
				'tag'			=> 'T4',
				'description'	=> 'blah',
				'emblem'		=> null
			],
			[
				'name'			=> 'Team 5',
				'slug'			=> 'Team5',
				'tag'			=> 'T5',
				'description'	=> 'blah',
				'emblem'		=> null
			],
			[
				'name'			=> 'Team 6',
				'slug'			=> 'Team6',
				'tag'			=> 'T6',
				'description'	=> 'blah',
				'emblem'		=> null
			],
			[
				'name'			=> 'Team 7',
				'slug'			=> 'Team7',
				'tag'			=> 'T7',
				'description'	=> 'blah',
				'emblem'		=> null
			],
		]);
    }
}
