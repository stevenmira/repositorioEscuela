<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaGradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grado')->insert([
        	//'idgrado' => 1,
        	'nombre' =>'Primero',
        	'estado' =>'Activo',
        ]);

        DB::table('grado')->insert([
        	//'idgrado' => 2,
        	'nombre' =>'Segundo',
        	'estado' =>'Activo',
        ]);

        DB::table('grado')->insert([
        	//'idgrado' => 3,
        	'nombre' =>'Tercero',
        	'estado' =>'Activo',
        ]);

        DB::table('grado')->insert([
        	//'idgrado' => 4,
        	'nombre' =>'Cuarto',
        	'estado' =>'Activo',
        ]);

        DB::table('grado')->insert([
        	//'idgrado' => 5,
        	'nombre' =>'Quinto',
        	'estado' =>'Activo',
        ]);

        DB::table('grado')->insert([
        	//'idgrado' => 6,
        	'nombre' =>'Sexto',
        	'estado' =>'Activo',
        ]);

        DB::table('grado')->insert([
        	//'idgrado' => 7,
        	'nombre' =>'Séptimo',
        	'estado' =>'Activo',
        ]);

        DB::table('grado')->insert([
        	//'idgrado' => 8,
        	'nombre' =>'Octavo',
        	'estado' =>'Activo',
        ]);

        DB::table('grado')->insert([
        	//'idgrado' => 9,
        	'nombre' =>'Noveno',
        	'estado' =>'Activo',
        ]);

    }
}
