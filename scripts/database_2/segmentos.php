<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_segmentos = new table_segmentos;
class table_segmentos
{
    protected $table = "segmentos";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->string('CI')->primary();
            $table->string('Segmento');
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
                'CI'        => '1',
                'Segmento'  => 'President',
            ],
            [
                'CI'        => '2',
                'Segmento'  => 'Masivo',
            ],
            [
                'CI'        => '3',
                'Segmento'  => 'Oportunidad Top',
            ]
        ]);
        // DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}