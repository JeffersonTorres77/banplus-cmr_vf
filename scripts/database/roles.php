<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_roles = new table_roles;
class table_roles
{
    protected $table = "roles";

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
                'nombre' => 'Admin',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '2',
                'nombre' => 'Centro de atención',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => '3',
                'nombre' => 'Gerencia',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}