<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $this->call(TablaDepartamentoSeeder::class);
        $this->call(TablaMunicipioSeeder::class);
        $this->call(TablaTrimestreSeeder::class);
        $this->call(TablaActividadSeeder::class);
        $this->call(TablaAniosSeeder::class);
        $this->call(TablaCategoriaSeeder::class);
        $this->call(TablaClaseSeeder::class);
        $this->call(TablaEstadoCivilSeeder::class);
        $this->call(TablaGradoSeeder::class);
        $this->call(TablaMateriaSeeder::class);
        $this->call(TablaNivelSeeder::class);
        $this->call(TablaResponsableSeeder::class);
        $this->call(TablaSeccionSeeder::class);       
        $this->call(TablaTurnoSeeder::class);
        $this->call(TablaTipoUsuarioSeeder::class);

    }
}
