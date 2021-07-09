<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_membresia_president = new table_membresia_president;
class table_membresia_president
{
    protected $table = "membresia_president";

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
                'nombre' => 'Membresía Pagada',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '2',
                'nombre' => 'En espera de pago',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}