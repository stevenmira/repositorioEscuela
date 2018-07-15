<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaTipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos_usuario')->insert([
        	'tipoUsuario' => 1,
        	'nombre' =>'administrador',
        ]);

        DB::table('tipos_usuario')->insert([
        	'tipoUsuario' => 2,
        	'nombre' =>'estandar',
        ]);
    }
}
