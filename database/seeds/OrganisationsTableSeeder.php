<?php

use Illuminate\Database\Seeder;

class OrganisationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organisations')->insert([
        	'name'			=> 'Laser Tag League',
        	'description'	=> 'The first laser tag league of Belgium',
        	'banner'		=> 'img/organisations/LTL.jpg',
        	'thumb'			=> null,
        	'trusted'		=> 1
        ]);
    }
}
