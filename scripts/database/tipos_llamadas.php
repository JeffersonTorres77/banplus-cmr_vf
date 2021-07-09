<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_tipos_llamadas = new table_tipos_llamadas;
class table_tipos_llamadas
{
    protected $table = "tipos_llamadas";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');
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
                'nombre' => 'Llamada Entrante',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '2',
                'nombre' => 'Llamada Saliente',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}