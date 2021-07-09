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
                'tipo_id' => '1',
                'nombre' => 'Se solvento reclamo',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '2',
                'tipo_id' => '1',
                'nombre' => 'Pendiente por solventar',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '3',
                'tipo_id' => '2',
                'nombre' => 'Cliente informado',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '4',
                'tipo_id' => '3',
                'nombre' => 'Interesado',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '5',
                'tipo_id' => '3',
                'nombre' => 'No interesado',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}