<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_permisos_roles = new table_permisos_roles;
class table_permisos_roles
{
    protected $table = "permisos_roles";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');

            $table->integer('rol_id')->unsigned()->index();
            $table->foreign('rol_id')->references('id')->on('roles');

            $table->integer('permiso_id')->unsigned()->index();
            $table->foreign('permiso_id')->references('id')->on('permisos');
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
                'id' => 1,
                'rol_id' => 1, 'permiso_id' => 1
            ],
            [
                'id' => 2,
                'rol_id' => 1, 'permiso_id' => 2
            ],
            [
                'id' => 3,
                'rol_id' => 1, 'permiso_id' => 3
            ],
            [
                'id' => 4,
                'rol_id' => 1, 'permiso_id' => 4
            ],
            [
                'id' => 5,
                'rol_id' => 2, 'permiso_id' => 3
            ],
            [
                'id' => 6,
                'rol_id' => 2, 'permiso_id' => 4
            ],
            [
                'id' => 7,
                'rol_id' => 3, 'permiso_id' => 3
            ],
            [
                'id' => 8,
                'rol_id' => 4, 'permiso_id' => 4
            ],
            [
                'id' => 9,
                'rol_id' => 1, 'permiso_id' => 5
            ],
            [
                'id' => 10,
                'rol_id' => 1, 'permiso_id' => 6
            ],
            [
                'id' => 11,
                'rol_id' => 1, 'permiso_id' => 7
            ],
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}