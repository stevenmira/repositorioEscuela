<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Primer Trimestre

        DB::table('actividad')->insert([
        	'id_actividad' => 1,
        	'id_trimestre' => 1,
        	'nombre' =>'Integradora',
        	'porcentaje' => 30,
        ]);

        DB::table('actividad')->insert([
        	'id_actividad' => 2,
        	'id_trimestre' => 1,
        	'nombre' =>'Cuaderno',
        	'porcentaje' => 20,
        ]);

        DB::table('actividad')->insert([
        	'id_actividad' => 3,
        	'id_trimestre' => 1,
        	'nombre' =>'Proyecto',
        	'porcentaje' => 10,
        ]);

        DB::table('actividad')->insert([
        	'id_actividad' => 4,
        	'id_trimestre' => 1,
        	'nombre' =>'Prueba Objetiva',
        	'porcentaje' => 40,
        ]);

        //Segundo Trimestre

        DB::table('actividad')->insert([
        	'id_actividad' => 5,
        	'id_trimestre' => 2,
        	'nombre' =>'Integradora',
        	'porcentaje' => 30,
        ]);

        DB::table('actividad')->insert([
        	'id_actividad' => 6,
        	'id_trimestre' => 2,
        	'nombre' =>'Cuaderno',
        	'porcentaje' => 20,
        ]);

        DB::table('actividad')->insert([
        	'id_actividad' => 7,
        	'id_trimestre' => 2,
        	'nombre' =>'Proyecto',
        	'porcentaje' => 10,
        ]);

        DB::table('actividad')->insert([
        	'id_actividad' => 8,
        	'id_trimestre' => 2,
        	'nombre' =>'Prueba Objetiva',
        	'porcentaje' => 40,
        ]);


        //Tercer Trimestre

        DB::table('actividad')->insert([
        	'id_actividad' => 9,
        	'id_trimestre' => 3,
        	'nombre' =>'Integradora',
        	'porcentaje' => 30,
        ]);

        DB::table('actividad')->insert([
        	'id_actividad' => 10,
        	'id_trimestre' => 3,
        	'nombre' =>'Cuaderno',
        	'porcentaje' => 20,
        ]);

        DB::table('actividad')->insert([
        	'id_actividad' => 11,
        	'id_trimestre' => 3,
        	'nombre' =>'Proyecto',
        	'porcentaje' => 10,
        ]);

        DB::table('actividad')->insert([
        	'id_actividad' => 12,
        	'id_trimestre' => 3,
        	'nombre' =>'Prueba Objetiva',
        	'porcentaje' => 40,
        ]);


    }
}
