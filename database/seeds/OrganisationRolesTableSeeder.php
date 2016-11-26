<?php

use Illuminate\Database\Seeder;

class OrganisationRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organisation_roles')->insert([
        	'user_id'			=> 2,
        	'organisation_id'	=> 1,
            'role'              => 'admin'
        ]);
    }
}
