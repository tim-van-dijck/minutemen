<?php

use Illuminate\Database\Seeder;

class OrganisationAdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organisation_admins')->insert([
        	'user_id'			=> 2,
        	'organisation_id'	=> 1,
        ]);
    }
}
