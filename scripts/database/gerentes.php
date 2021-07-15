<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_gerentes = new table_gerentes;
class table_gerentes
{
    protected $table = "gerentes";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');
            $table->string('region');
            $table->string('nombre');
            $table->timestamps();
        });

        return $this;
    }

    public function down() {
        DB::schema()->dropIfExists($this->table);

        return $this;
    }

    public function default() {
        // DB::unprepared("SET IDENTITY_INSERT {$this->table} ON");
        $users = DB::table($this->table)->insert([
            [
                'region' => 'AGROPECUARIA', 'nombre' => 'Jose Bencomo',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'AGROPECUARIA', 'nombre' => 'Gustavo Velasquez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRAL', 'nombre' => 'Deyamig Viloria',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRAL', 'nombre' => 'Juan Espinoza',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRAL', 'nombre' => 'Yuliana Ferrer',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRAL', 'nombre' => 'Carlos Castillo',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRAL', 'nombre' => 'Maiyuli Palencia',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRAL', 'nombre' => 'Dalila Pernalete',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRAL', 'nombre' => 'Gerente Puerto Cabello',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRO OCCIDENTE', 'nombre' => 'Javier Freitez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRO OCCIDENTE', 'nombre' => 'Claudia Martinez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRO OCCIDENTE', 'nombre' => 'Yelihtze Rodriguez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CENTRO OCCIDENTE', 'nombre' => 'Yorladys Jerez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'COMERCIO', 'nombre' => 'Mirla Ledezma',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'COMERCIO', 'nombre' => 'Peggy Herrera',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'COMERCIO', 'nombre' => 'Loreiza Lopez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'COMERCIO', 'nombre' => 'Lisbeth Blanco',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'COMERCIO', 'nombre' => 'Karina Davila',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'COMERCIO', 'nombre' => 'Eloisa Aguilar',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'COMERCIO', 'nombre' => 'Zoraida Ramos',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CORP. Y EMPRESAS', 'nombre' => 'Jean Castellanos',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CORP. Y EMPRESAS', 'nombre' => 'Alexander Pareles',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CORP. Y EMPRESAS', 'nombre' => 'Angela Vecchio',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CORP. Y EMPRESAS', 'nombre' => 'Yelitza Alvarez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CORP. Y EMPRESAS', 'nombre' => 'Maurys Larez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CORP. Y EMPRESAS', 'nombre' => 'Alejandra Arvelo',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CORP. Y EMPRESAS', 'nombre' => 'Adymar Espinoza',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CORP. Y EMPRESAS', 'nombre' => 'Maria Paola Demari',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'CORP. Y EMPRESAS', 'nombre' => 'Claudia Valenzuela',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'INSULAR', 'nombre' => 'Aurelina Gonzalez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'INSULAR', 'nombre' => 'Giorgy Landaeta',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'INSULAR', 'nombre' => 'Patricia Garcia',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'LOS ANDES', 'nombre' => 'Maria Madrid',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'LOS ANDES', 'nombre' => 'Maira Molina',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'LOS ANDES', 'nombre' => 'Franklin Mora',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'LOS ANDES', 'nombre' => 'Jennifer Narvaez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'OCCIDENTE', 'nombre' => 'Gerente Las Delicias 2',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'OCCIDENTE', 'nombre' => 'Melina Viloria',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'OCCIDENTE', 'nombre' => 'Eureny Nava',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'OCCIDENTE', 'nombre' => 'Romny Leon',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'OCCIDENTE', 'nombre' => 'Maria Fernandez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'OCCIDENTE', 'nombre' => 'Ana Cariel',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'OCCIDENTE', 'nombre' => 'Yasmile Mejia',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'ORIENTE NORTE', 'nombre' => 'Angeline Bertoletti',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'ORIENTE NORTE', 'nombre' => 'Liborio Barroso 2',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'ORIENTE NORTE', 'nombre' => 'Liborio Barroso 1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'ORIENTE SUR', 'nombre' => 'Neyda Leal',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'ORIENTE SUR', 'nombre' => 'Nellys Rodriguez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'ORIENTE SUR', 'nombre' => 'Indira Rodriguez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'ORIENTE SUR', 'nombre' => 'Yadnisa Muñoz',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'ORIENTE SUR', 'nombre' => 'Mairyn Llovera',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'ORIENTE SUR', 'nombre' => 'Heidy Alexander',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'ORIENTE SUR', 'nombre' => 'Libeth Castellin',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'PYMES', 'nombre' => 'Johanna Bellorin',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'PYMES', 'nombre' => 'Mindred Ramirez',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'PYMES', 'nombre' => 'Liliana Chinchilla',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'PYMES', 'nombre' => 'Cesar Herrera',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'PYMES', 'nombre' => 'Ingrid Ball',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'PYMES', 'nombre' => 'Liliana Isturiz',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'PYMES', 'nombre' => 'Thinay Brito',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'region' => 'PYMES', 'nombre' => 'Miguel Linares',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);
        // DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}