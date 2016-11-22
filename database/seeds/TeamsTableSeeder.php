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
			'name'			=> 'Rake Poepklappers',
			'slug'			=> 'RakePoepklappers',
			'tag'			=> 'POEP',
			'description'	=> 'De Poepklappers die zowaar steeds raak klappen!',
			'emblem'		=> null,
		]);

		DB::table('teams')->insert([
			'name'			=> 'Team Supertof',
			'slug'			=> 'TeamSupertof',
			'tag'			=> 'STOF',
			'description'	=> 'Het supertofste team!!',
			'emblem'		=> null,
		]);

		DB::table('teams')->insert([
			'name'			=> 'De Wally\'s',
			'slug'			=> 'DeWallys',
			'tag'			=> 'WAUW',
			'description'	=> '<p>Ik spring uit een vliegmasjien</p>',
			'emblem'		=> null,
		]);
    }
}
