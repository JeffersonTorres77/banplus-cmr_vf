<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_clientes_temporales = new table_clientes_temporales;
class table_clientes_temporales
{
    protected $table = "clientes_temporales";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');
            $table->string('cedula');
            $table->string('nombre');
            $table->string('segmento');
            $table->string('celular');
            $table->string('otro_telefono')->nullable();
            $table->string('correo');
            $table->string('gerente_banca_persona');
            $table->string('gerente_juridico');
            $table->string('vpr_juridico');
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