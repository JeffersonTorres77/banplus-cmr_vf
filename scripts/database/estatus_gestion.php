<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_estatus_gestion = new table_estatus_gestion;
class table_estatus_gestion
{
    protected $table = "estatus_gestion";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');

            $table->integer('tipo_id')->unsigned()->index()->nullable();
            $table->foreign('tipo_id')->references('id')->on('tipos_gestion');

            $table->string('nombre');
            $table->boolean('de_cierre');
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
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '2', 'tipo_id' => '1',
                'nombre' => 'Pendiente por solventar',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '3', 'tipo_id' => '2',
                'nombre' => 'Cliente informado',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '4', 'tipo_id' => '3',
                'nombre' => 'Interesado',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '5', 'tipo_id' => '3',
                'nombre' => 'No interesado',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '6', 'tipo_id' => '1',
                'nombre' => 'Se asignó reclamo a CAP',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '7', 'tipo_id' => '2',
                'nombre' => 'Sin posible contacto',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '8', 'tipo_id' => '3',
                'nombre' => 'Sin posible contacto',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '9', 'tipo_id' => '4',
                'nombre' => 'Interesado',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '10', 'tipo_id' => '4',
                'nombre' => 'Se agenda cita',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '11', 'tipo_id' => '4',
                'nombre' => 'No interesado',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '12', 'tipo_id' => '4',
                'nombre' => 'Sin posible contacto',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '13', 'tipo_id' => '4',
                'nombre' => 'Ya posee producto ofertado',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '14', 'tipo_id' => '5',
                'nombre' => 'Se comprometío a pagar',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '15', 'tipo_id' => '5',
                'nombre' => 'No acepto pagar',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '16', 'tipo_id' => '5',
                'nombre' => 'Sin posible contacto',
                'de_cierre' => '0',
                'ver_centro_atencion' => '0', 'ver_gerente' => '1',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '17', 'tipo_id' => NULL,
                'nombre' => 'Pendiente',
                'de_cierre' => '0',
                'ver_centro_atencion' => '1', 'ver_gerente' => '0',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '18', 'tipo_id' => NULL,
                'nombre' => 'Cerrado',
                'de_cierre' => '1',
                'ver_centro_atencion' => '1', 'ver_gerente' => '0',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}