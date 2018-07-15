<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TablaMunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('municipio')->truncate();


        ///	Ahuachapán	(12)

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'Ahuachapán',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'Apaneca',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'Atiquizaya',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'Concepción de Ataco',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'El Refugio',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'Guaymango',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'Jujutla',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'San Francisco Menéndez',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'San Lorenzo',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'San Pedro Puxtla',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'Tacuba',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 1,
        	'nombre' => 'Turín',
        ]);

        /// Cabañas	(9)

        DB::table('municipio')->insert([
        	'id_departamento' => 2,
        	'nombre' => 'Cinquera',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 2,
        	'nombre' => 'Dolores / Villa Dolores',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 2,
        	'nombre' => 'Guacotecti',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 2,
        	'nombre' => 'Ilobasco',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 2,
        	'nombre' => 'Jutiapa',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 2,
        	'nombre' => 'San Isidro',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 2,
        	'nombre' => 'Sensuntepeque',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 2,
        	'nombre' => 'Tejutepeque',
        ]);

        DB::table('municipio')->insert([
        	'id_departamento' => 2,
        	'nombre' => 'Victoria',
        ]);

        /// Chalatenango (33)

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Agua Caliente',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Arcatao',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Azaculapa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Chalatenango',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Citalá',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Comalapa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Concepción Quezaltepeque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Dulce Nombre de María',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'El Carrizal',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'El Paraíso',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'La Laguna',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'La Palma',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'La Reina',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Las Vueltas',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Nombre de Jesús',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Nueva Concepción',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Nueva Trinidad',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'ojos de Agua',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Potonico',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Antonio de la Cruz',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Antonio de Los Ranchos',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Fernando',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Francisco Lempa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Francisco Morazán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Ignacio',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Isidro Labrador',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San José Cancasque / Cancasque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Jose Las Flores / Las Flores',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Luis del Carmen',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Miguel de Mercedes',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'San Rafael',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Santa Rita',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 3,
            'nombre' => 'Tejutla',
        ]);


        /// Cuscatlán (16)

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'Candelaria',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'Cojutepeque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'El Carmen',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'El Rosario',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'Monte San Juan',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'Oratorio de Concepción',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'San Bartolomé Perulapía',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'San Cristóbal',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'San José Guayabal',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'San Pedro Perulapán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'San Rafael Cedros',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'San Ramón',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'Santa Cruz Analquito',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'Santa Cruz Michapa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'Suchitoto',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 4,
            'nombre' => 'Tenancingo',
        ]);

        /// La Libertad (22)

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Antiguo Cuscatlán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Chiltiupán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Ciudad Arce',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Colón',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Comasagua',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Huizúcar',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Jayaque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Jicalapa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'La Libertad',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Santa Tecla. Antes: Nueva San Salvador',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Nuevo Cuscatlán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'San Juan Opico',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Quezaltepeque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Sacacoyo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'San José Villanueva',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'San Matías',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'San Pablo Tacachico',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Talnique',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Tamanique',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Teotepeque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Tepecoyo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 5,
            'nombre' => 'Zaragoza',
        ]);

        /// La Paz (22)

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'Cuyultitán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'El Rosario / Rosario de la Paz',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'Jerusalén',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'Mercedes la Ceiba',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'Olocuilta',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'Paraíso de Osorio',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Antonio Masahuat',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Emigdio',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Francisco Chinameca',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Juan Nonualco',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Juan Talpa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Juan Tepezontes',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Luis la Herradura',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Luis Talpa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Miguel Tepezontes',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Pedro Masahuat',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Pedro Nonualco',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'San Rafael Obrajuelo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'Santa María Ostuma',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'Santiago Nonualco',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'Talpalhuaca',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 6,
            'nombre' => 'Zacatecoluca',
        ]);

        /// La Unión (18)

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Anamorós',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Bolívar',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Concepción de Oriente',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Conchagua',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'El Carmen',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'El Sauce',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Intipucá',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'La Unión',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Lilísque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Meanguera del Golfo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Nueva Esparta',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Pasaquina',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Polorós',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'San Alejo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'San José',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Santa Rosa de Lima',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Yayantique',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 7,
            'nombre' => 'Yucaiquín',
        ]);

        /// Morazán (26)

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Arambala',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Cacaopera',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Chilanga',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Corinto',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Delicias de Concepción',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'El Divisadero',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'El Rosario',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Gualococti',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Guatajiagua',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Joateca',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Jocoaitique',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Jocoro',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Lolotiquillo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Meanguera',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Osicala',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Perquín',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'San Carlos',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'San Fernando',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'San Francisco Gotera',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'San Isidro',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'San Simón',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Sensembra',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Sociedad',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Torola',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Yamabal',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 8,
            'nombre' => 'Yoloaiquín',
        ]);

        /// San Miguel (20)

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Carolina',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Chapeltique',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Chinameca',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Chirilagua',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Ciudad Barrios',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Comacarán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'El Tránsito',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Lolotique',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Moncagua',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Nueva Guadalupe',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Nuevo Edén de San Juan',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Quelepa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'San Antonio del Mosco',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'San Gerardo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'San Jorge',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'San Luis de la Reina',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'San Miguel',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'San Rafael Oriente',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Sesori',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 9,
            'nombre' => 'Uluazapa',
        ]);


        /// San Salvador (19)

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Aguilares',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Apopa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Ayutuxtepeque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Delgado',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Cuscatancingo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'El Paisnal',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Guazapa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Ilopango',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Mejicanos',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Nejapa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Panchimalco',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Rosario de Mora',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'San Marcos',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'San Martín',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'San Salvador',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Santiago Texacuangos',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Santo Tomás',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Soyapango',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 10,
            'nombre' => 'Tonacatepeque',
        ]);


        /// San Vicente (13)

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'Apastepeque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'Guadalupe',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'San Cayetano Istepeque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'San Esteban Catarina',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'San Ildefonso',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'San Lorenzo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'San Sebastián',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'San Vicente',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'Santa Clara',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'Santo Domingo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'Tecoluca',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'Tepetitán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 11,
            'nombre' => 'Verapaz',
        ]);

        /// Santa Ana (13)

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'Candelaria de la Frontera',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'Chalchuapa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'Coatepeque',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'El Congo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'El Porvenir',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'Masahuat',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'Metapán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'San Antonio Pajonal',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'San Sebastián Salitrillo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'Santa Ana',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'Santa Rosa Guachipilín',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'Santiago de la Frontera',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 12,
            'nombre' => 'Texistepeque',
        ]);

        /// Sonsonate (16)

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Acajutla',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Armenia',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Caluco',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Cuisnahuat',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Izalco',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Juayúa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Nahuizalco',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Nahulingo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Salcoatitán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'San Antonio del Monte',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'San Julián',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Santa Catarina Masahuat',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Santa Isabel Ishuatán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Santo Domingo de Guzmán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Sonsonate',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 13,
            'nombre' => 'Sonzacate',
        ]);


        /// Usulután (23)

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Alegría',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Berlín',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'California',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Concepción Batres',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'El Triunfo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Ereguayquín',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Estanzuelas',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Jiquilisco',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Jucuapa',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Jucuarán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Mercedes Umaña',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Nueva Granada',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Ozatlán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Puerto el Triunfo',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'San Agustín',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'San Buenaventura',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'San Dionisio',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'San Francisco Javier',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Santa Elena',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Santa María',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Santiago de María',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Tecapán',
        ]);

        DB::table('municipio')->insert([
            'id_departamento' => 14,
            'nombre' => 'Usulután',
        ]);
        
    }
}
























