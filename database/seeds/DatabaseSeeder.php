<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(UsersTableSeeder::class);
		$this->call(TeamsTableSeeder::class);
		$this->call(TeamUsersTableSeeder::class);
		$this->call(FriendshipsTableSeeder::class);
		$this->call(OrganisationsTableSeeder::class);
		$this->call(OrganisationRolesTableSeeder::class);
		$this->call(EventsTableSeeder::class);
	}
}
