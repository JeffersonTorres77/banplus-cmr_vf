<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_tipos_gestion = new table_tipos_gestion;
class table_tipos_gestion
{
    protected $table = "tipos_gestion";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('ver_centro_atencion');
            $table->string('ver_gerente');
            $table->timestamps();
        });

        return $this;
    }

    public function down() {
        DB::schema()->dropIfExists($this->table);

        return $this;
    }

    public function default() {
        DB::unprepared("SET IDENTITY_INSERT {$this->table} ON");
        $users = DB::table($this->table)->insert([
            [
                'id' => '1',
                'nombre' => 'Reclamo',
                'ver_centro_atencion' => '1', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '2',
                'nombre' => 'Informativa',
                'ver_centro_atencion' => '1', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '3',
                'nombre' => 'Promoción',
                'ver_centro_atencion' => '1', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '4',
                'nombre' => 'Oferta de producto',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '5',
                'nombre' => 'Cobranza',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}