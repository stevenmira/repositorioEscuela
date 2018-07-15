<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaDepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('departamento')->truncate();

        DB::table('departamento')->insert([
            'id_departamento' => 1,
        	'nombre' =>'Ahuachapán',
        	//'codigo' => 01,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 2,
        	'nombre' =>'Cabañas',
        	//'codigo' => 09,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 3,
        	'nombre' =>'Chalatenango',
        	//'codigo' => 04,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 4,
        	'nombre' =>'Cuscatlán',
        	//'codigo' => 07,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 5,
        	'nombre' =>'La Libertad',
        	//'codigo' => 05,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 6,
        	'nombre' =>'La Paz',
        	//'codigo' => 08,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 7,
        	'nombre' =>'La Unión',
        	//'codigo' => 14,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 8,
        	'nombre' =>'Morazán',
        	//'codigo' => 13,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 9,
        	'nombre' =>'San Miguel',
        	//'codigo' => 12,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 10,
        	'nombre' =>'San Salvador',
        	//'codigo' => 06,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 11,
        	'nombre' =>'San Vicente',
        	//'codigo' => 10,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 12,
        	'nombre' =>'Santa Ana',
        	//'codigo' => 02,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 13,
        	'nombre' =>'Sonsonate',
        	//'codigo' => 03,
        ]);

        DB::table('departamento')->insert([
            'id_departamento' => 14,
        	'nombre' =>'Usulután',
        	//'codigo' => 11,
        ]);
    }
}
