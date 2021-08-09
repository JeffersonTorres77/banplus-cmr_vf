<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_gestiones = new table_gestiones;
class table_gestiones
{
    protected $table = "gestiones";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');
            $table->date('fecha_asignacion')->nullable();
            $table->date('fecha_gestion');
            $table->integer('ci');

            $table->integer('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('usuarios');

            $table->integer('tipo_llamada_id')->unsigned()->index();
            $table->foreign('tipo_llamada_id')->references('id')->on('tipos_llamadas');

            $table->integer('tipo_gestion_id')->unsigned()->index()->nullable();
            $table->foreign('tipo_gestion_id')->references('id')->on('tipos_gestion');

            $table->integer('estatus_gestion_id')->unsigned()->index();
            $table->foreign('estatus_gestion_id')->references('id')->on('estatus_gestion');

            $table->text('comentario')->nullable();

            $table->integer('resolucion_comite_id')->unsigned()->index()->nullable();
            $table->foreign('resolucion_comite_id')->references('id')->on('resolucion_comite');

            $table->date('fecha_comite')->nullable();

            $table->integer('membresia_president_id')->unsigned()->index()->nullable();
            $table->foreign('membresia_president_id')->references('id')->on('membresia_president');

            $table->date('fecha_pago')->nullable();
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
        // $users = DB::table($this->table)->insert([]);
        // DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}