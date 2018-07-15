<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaTurnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('turno')->insert([
        	//'idturno' => 1,
        	'nombre' =>'Matutino',
        	'estado' => 'Activo',
        ]);

        DB::table('turno')->insert([
        	//'idturno' => 2,
        	'nombre' =>'Vespertino',
        	'estado' => 'Activo',
        ]);
    }
}
