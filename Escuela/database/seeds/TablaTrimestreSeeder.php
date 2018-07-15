<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaTrimestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('trimestre')->insert([
        	//'id_trimestre' => 1,
        	'nombre' =>'Trimestre I',
        ]);

        DB::table('trimestre')->insert([
        	//'id_trimestre' => 2,
        	'nombre' =>'Trimestre II',
        ]);

        DB::table('trimestre')->insert([
        	//'id_trimestre' => 3,
        	'nombre' =>'Trimestre III',
        ]);
    }
}
