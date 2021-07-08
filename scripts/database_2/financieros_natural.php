<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_financieros_natural = new table_financieros_natural;
class table_financieros_natural
{
    protected $table = "financieros_natural";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->string('CI')->primary();
            $table->string('Cuenta');
            $table->string('Moneda');
            $table->string('Modalidad_Cuenta');
            $table->string('Categoria_Cuenta');
            $table->string('Producto');
            $table->date('Apertura');
            $table->float('Promedio_Mes_1');
            $table->float('Promedio_Mes_2');
            $table->float('Promedio_Mes_3');
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
                'CI'                => '1',
                'Cuenta'            => '01741234567890123456',
                'Moneda'            => 'Bolívar',
                'Modalidad_Cuenta'  => 'Unipersonal',
                'Categoria_Cuenta'  => 'Clásica',
                'Producto'          => 'Corriente',
                'Apertura'          => '10/9/2010',
                'Promedio_Mes_1'    => '23423.34',
                'Promedio_Mes_2'    => '23432',
                'Promedio_Mes_3'    => '54564.3',
            ],
            [
                'CI'                => '2',
                'Cuenta'            => '01740987654321987654',
                'Moneda'            => 'Euro',
                'Modalidad_Cuenta'  => 'Conjunta',
                'Categoria_Cuenta'  => 'President',
                'Producto'          => 'Custodia Euros',
                'Apertura'          => '11/9/2015',
                'Promedio_Mes_1'    => '64565464.457',
                'Promedio_Mes_2'    => '4564564.6',
                'Promedio_Mes_3'    => '56456.455',
            ],
            [
                'CI'                => '3',
                'Cuenta'            => '01741234554321678900',
                'Moneda'            => 'Dólar',
                'Modalidad_Cuenta'  => 'Indistinta',
                'Categoria_Cuenta'  => 'Máxima 1',
                'Producto'          => 'Custodia Dólares',
                'Apertura'          => '12/9/2013',
                'Promedio_Mes_1'    => '32432.4',
                'Promedio_Mes_2'    => '978986',
                'Promedio_Mes_3'    => '47976',
            ],
        ]);
        // DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}