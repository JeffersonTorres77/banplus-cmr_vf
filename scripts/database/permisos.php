<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_permisos = new table_permisos;
class table_permisos
{
    protected $table = "permisos";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('description');
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
                'id' => 1,
                'slug' => 'menu_roles',
                'description' => 'Menu de roles',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 2,
                'slug' => 'menu_usuarios',
                'description' => 'Menu de usuarios',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 3,
                'slug' => 'menu_gestion_contacto',
                'description' => 'Menu de gestion',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 4,
                'slug' => 'menu_gestion_contacto_gerencia',
                'description' => 'Menu de gestion (Gerencia)',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 5,
                'slug' => 'gestion_eliminar',
                'description' => 'Eliminar gestiones',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 6,
                'slug' => 'gestion_cerrar',
                'description' => 'Cerrar gestiones',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);
        DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}