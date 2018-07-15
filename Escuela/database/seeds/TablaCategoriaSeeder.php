<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categoria')->insert([
        	//'id_categoria' => 1,
        	'categoria' =>'AA',
        ]);

        DB::table('categoria')->insert([
        	//'id_categoria' => 2,
        	'categoria' =>'BB',
        ]);
    }
}
