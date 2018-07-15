<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaClaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clase')->insert([
        	//'id_clase' => 1,
        	'clase' =>'A',
        ]);

        DB::table('clase')->insert([
        	//'id_clase' => 2,
        	'clase' =>'B',
        ]);

        DB::table('clase')->insert([
        	//'id_clase' => 3,
        	'clase' =>'C',
        ]);

        DB::table('clase')->insert([
        	//'id_clase' => 4,
        	'clase' =>'D',
        ]);
        
    }
}
