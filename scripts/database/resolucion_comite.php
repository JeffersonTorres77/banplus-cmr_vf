<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_resolucion_comite = new table_resolucion_comite;
class table_resolucion_comite
{
    protected $table = "resolucion_comite";

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
                'nombre' => 'Apto',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '2',
                'nombre' => 'En Comité',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '3',
                'nombre' => 'Diferido',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '4',
                'nombre' => 'No Apto',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}