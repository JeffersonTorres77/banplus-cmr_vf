<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_estatus_gestion = new table_estatus_gestion;
class table_estatus_gestion
{
    protected $table = "estatus_gestion";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');

            $table->integer('tipo_id')->unsigned()->index();
            $table->foreign('tipo_id')->references('id')->on('tipos_gestion');

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
                'id' => '1', 'tipo_id' => '1',
                'nombre' => 'Se solvento reclamo',
                'ver_centro_atencion' => '1', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '2', 'tipo_id' => '1',
                'nombre' => 'Pendiente por solventar',
                'ver_centro_atencion' => '1', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '3', 'tipo_id' => '2',
                'nombre' => 'Cliente informado',
                'ver_centro_atencion' => '1', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '4', 'tipo_id' => '3',
                'nombre' => 'Interesado',
                'ver_centro_atencion' => '1', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '5', 'tipo_id' => '3',
                'nombre' => 'No interesado',
                'ver_centro_atencion' => '1', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '6', 'tipo_id' => '1',
                'nombre' => 'Se asignó reclamo a CAP',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '7', 'tipo_id' => '2',
                'nombre' => 'Sin posible contacto',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '8', 'tipo_id' => '3',
                'nombre' => 'Sin posible contacto',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '9', 'tipo_id' => '4',
                'nombre' => 'Interesado',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '10', 'tipo_id' => '4',
                'nombre' => 'Se agenda cita',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '11', 'tipo_id' => '4',
                'nombre' => 'No interesado',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '12', 'tipo_id' => '4',
                'nombre' => 'Sin posible contacto',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '13', 'tipo_id' => '4',
                'nombre' => 'Ya posee producto ofertado',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '14', 'tipo_id' => '5',
                'nombre' => 'Se comprometío a pagar',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '15', 'tipo_id' => '5',
                'nombre' => 'No acepto pagar',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '16', 'tipo_id' => '5',
                'nombre' => 'Sin posible contacto',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}