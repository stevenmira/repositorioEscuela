<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaAniosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('anios')->insert([
        	//'idanio' => 1,
        	'valor' => 2017,
        ]);

        DB::table('anios')->insert([
        	//'idanio' => 2,
        	'valor' => 2018,
        ]);

        DB::table('anios')->insert([
        	//'idanio' => 3,
        	'valor' => 2019,
        ]);

        DB::table('anios')->insert([
        	//'idanio' => 4,
        	'valor' => 2020,
        ]);

        DB::table('anios')->insert([
        	//'idanio' => 5,
        	'valor' => 2021,
        ]);

        DB::table('anios')->insert([
        	//'idanio' => 6,
        	'valor' => 2022,
        ]);

        DB::table('anios')->insert([
        	//'idanio' => 7,
        	'valor' => 2023,
        ]);

        DB::table('anios')->insert([
        	//'idanio' => 8,
        	'valor' => 2024,
        ]);

        DB::table('anios')->insert([
        	//'idanio' => 9,
        	'valor' => 2025,
        ]);
    }
}
