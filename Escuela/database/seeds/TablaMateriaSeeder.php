<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaMateriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('materia')->insert([
        	//'id_materia' => 1,
        	'nombre' =>'Lenguaje y Literatura',
        	'estado' =>'Activo',
        ]);

        DB::table('materia')->insert([
        	//'id_materia' => 2,
        	'nombre' =>'Matemática',
        	'estado' =>'Activo',
        ]);

        DB::table('materia')->insert([
        	//'id_materia' => 3,
        	'nombre' =>'Ciencia Salud y Medio Ambiente',
        	'estado' =>'Activo',
        ]);

        DB::table('materia')->insert([
        	//'id_materia' => 4,
        	'nombre' =>'Estudios Sociales',
        	'estado' =>'Activo',
        ]);

        DB::table('materia')->insert([
        	//'id_materia' => 5,
        	'nombre' =>'Segundo Idioma (Inglés)',
        	'estado' =>'Activo',
        ]);

        DB::table('materia')->insert([
        	//'id_materia' => 6,
        	'nombre' =>'Educación Física',
        	'estado' =>'Activo',
        ]);

        DB::table('materia')->insert([
        	//'id_materia' => 7,
        	'nombre' =>'Moral y Cívica',
        	'estado' =>'Activo',
        ]);

        DB::table('materia')->insert([
        	//'id_materia' => 8,
        	'nombre' =>'Informática',
        	'estado' =>'Activo',
        ]);

    }
}
