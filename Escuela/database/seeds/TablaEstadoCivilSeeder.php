<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaEstadoCivilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('estadocivil')->insert([
        	//'id_estado' => 1,
        	'tipo' =>'Soltero (a)',
        ]);

        DB::table('estadocivil')->insert([
        	//'id_estado' => 2,
        	'tipo' =>'Casado (a)',
        ]);

        DB::table('estadocivil')->insert([
        	//'id_estado' => 3,
        	'tipo' =>'Divorciado (a)',
        ]);

        DB::table('estadocivil')->insert([
        	//'id_estado' => 4,
        	'tipo' =>'Viudo (a)',
        ]);
    }
}
