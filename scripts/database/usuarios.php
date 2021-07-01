<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_usuarios = new table_usuarios;
class table_usuarios
{
    protected $table = "usuarios";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');

            $table->integer('rol_id')->unsigned()->index();
            $table->foreign('rol_id')->references('id')->on('roles');

            $table->string('usuario')->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('correo');
            $table->string('cargo')->nullable();
            $table->string('departamento')->nullable();
            $table->boolean('validar_red');
            $table->string('clave')->nullable();
            $table->boolean('activo');
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
                'rol_id'        => 1,
                'usuario'       => 'admin',
                'nombres'       => 'Administrador',
                'apellidos'     => 'Por Defecto',
                'correo'        => 'jjtorres@banplus.com',
                'cargo'         => NULL,
                'departamento'  => NULL,
                'validar_red'   => FALSE,
                'clave'         => 'admin',
                'activo'        => TRUE,
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}