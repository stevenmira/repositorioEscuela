<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaNivelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nivel')->insert([
        	//'id_nivel' => 1,
        	'nivel' => 1,
        ]);

        DB::table('nivel')->insert([
        	//'id_nivel' => 2,
        	'nivel' => 2,
        ]);

        DB::table('nivel')->insert([
        	//'id_nivel' => 3,
        	'nivel' => 3,
        ]);

        DB::table('nivel')->insert([
        	//'id_nivel' => 4,
        	'nivel' => 4,
        ]);
    }
}
