<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_contactos = new table_contactos;
class table_contactos
{
    protected $table = "contactos";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->string('CI')->primary();
            $table->string('Nro_Telefono');
            $table->string('Tipo');
            $table->string('Operadora');
            $table->string('Email');
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
                'CI'            => '123456',
                'Nro_Telefono'  => '581231231212',
                'Tipo'          => 'Móvil',
                'Operadora'     => 'Digitel',
                'Email'         => 'correo@gmail.com',
            ],
            [
                'CI'            => '987654',
                'Nro_Telefono'  => '581231234343',
                'Tipo'          => 'Fijo',
                'Operadora'     => 'Fijo',
                'Email'         => 'correo@gmail.com',
            ],
            [
                'CI'            => '555444',
                'Nro_Telefono'  => '581231237777',
                'Tipo'          => 'Móvil',
                'Operadora'     => 'Movistar',
                'Email'         => 'correo@gmail.com',
            ]
        ]);
        // DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}