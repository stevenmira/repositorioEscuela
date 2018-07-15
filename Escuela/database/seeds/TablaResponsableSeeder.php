<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaResponsableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_responsable')->insert([
        	//'idresponsable' => 1,
        	'nombretipo' =>'Madre',
        ]);

        DB::table('tipo_responsable')->insert([
        	//'idresponsable' => 2,
        	'nombretipo' =>'Padre',
        ]);

        DB::table('tipo_responsable')->insert([
        	//'idresponsable' => 3,
        	'nombretipo' =>'Contacto',
        ]);
    }
}
