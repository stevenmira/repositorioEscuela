<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaSeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seccion')->insert([
        	//'idseccion' => 1,
        	'nombre' =>'A',
        	'estado' => 'Activo',
        ]);

        DB::table('seccion')->insert([
        	//'idseccion' => 2,
        	'nombre' =>'B',
        	'estado' => 'Activo',
        ]);

        DB::table('seccion')->insert([
        	//'idseccion' => 3,
        	'nombre' =>'C',
        	'estado' => 'Activo',
        ]);
    }
}
