<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_president = new table_president;
class table_president
{
    protected $table = "president";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->string('fecha_afiliacion');
            $table->string('gerente_atencion_origen');
            $table->string('region_origen');
            $table->string('gerente_atencion');
            $table->string('vicepresidencia');
            $table->string('segmento_cuenta');
            $table->string('t_ci');
            $table->string('ci')->primary();
            $table->string('nombre');
            $table->string('celular');
            $table->string('otro_celular');
            $table->string('correo');
            $table->string('segmento');
            $table->string('monto');
            $table->string('medio_pago');
            $table->string('lc');
            $table->string('ffvv');
            $table->string('estatus');
            $table->string('type');
            $table->string('grupo_final');
            $table->string('expediente');
            $table->string('contratos_uvc');
            $table->string('renewal');
            $table->string('tipo_credito');
            $table->string('limite_uvc');
            $table->string('numero_acta');
            $table->string('fecha_acta');
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
                'fecha_afiliacion'          => '28/09/2018',
                'gerente_atencion_origen'   => 'Luis Vielma',
                'region_origen'             => 'Banca de Empresas',
                'gerente_atencion'          => 'Miguel Linares',
                'vicepresidencia'           => 'Pyme',
                'segmento_cuenta'           => 'Cliente BI',
                't_ci'                      => 'VEN',
                'ci'                        => '1',
                'nombre'                    => 'a',
                'celular'                   => '414',
                'otro_celular'              => '414123',
                'correo'                    => 'a@GMAIL.COM',
                'segmento'                  => 'BLACK',
                'monto'                     => '300',
                'medio_pago'                => 'Custodia',
                'lc'                        => 'C',
                'ffvv'                      => '',
                'estatus'                   => '1',
                'type'                      => 'RENOVADO',
                'grupo_final'               => 'Grupo 2',
                'expediente'                => 'Completo',
                'contratos_uvc'             => 'Completo',
                'renewal'                   => '01/09/2021',
                'tipo_credito'              => 'Indexado',
                'limite_uvc'                => '204543.14',
                'numero_acta'               => 'CAP_20210211',
                'fecha_acta'                => '11/02/2021',
            ],
            [
                'fecha_afiliacion'          => '28/09/2018',
                'gerente_atencion_origen'   => 'Adymar Espinoza',
                'region_origen'             => 'Banca de Empresas',
                'gerente_atencion'          => 'Adymar Espinoza',
                'vicepresidencia'           => 'Corp. Grande Empresa',
                'segmento_cuenta'           => 'Cliente BI',
                't_ci'                      => 'VEN',
                'ci'                        => '2',
                'nombre'                    => 'b',
                'celular'                   => '416',
                'otro_celular'              => '416123',
                'correo'                    => 'b@GMAIL.COM',
                'segmento'                  => 'BLACK',
                'monto'                     => '500',
                'medio_pago'                => 'Afiliación Bi',
                'lc'                        => 'P',
                'ffvv'                      => '',
                'estatus'                   => '0',
                'type'                      => 'VENCIDO',
                'grupo_final'               => 'Sin Ajuste',
                'expediente'                => 'Completo',
                'contratos_uvc'             => 'Sin contrato',
                'renewal'                   => '01/09/2021',
                'tipo_credito'              => 'No indexado',
                'limite_uvc'                => '',
                'numero_acta'               => '',
                'fecha_acta'                => '',
            ],
            [
                'fecha_afiliacion'          => '28/09/2018',
                'gerente_atencion_origen'   => 'Adymar Espinoza',
                'region_origen'             => 'Banca de Empresas',
                'gerente_atencion'          => 'Adymar Espinoza',
                'vicepresidencia'           => 'Corp. Grande Empresa',
                'segmento_cuenta'           => 'Cliente BI',
                't_ci'                      => 'VEN',
                'ci'                        => '3',
                'nombre'                    => 'c',
                'celular'                   => '414',
                'otro_celular'              => '414456',
                'correo'                    => 'c@HOTMAIL.COM',
                'segmento'                  => 'BLACK',
                'monto'                     => '500',
                'medio_pago'                => 'Afiliación Bi',
                'lc'                        => 'C',
                'ffvv'                      => '',
                'estatus'                   => '1',
                'type'                      => 'RENOVADO',
                'grupo_final'               => 'Grupo 1',
                'expediente'                => 'Completo',
                'contratos_uvc'             => 'Completo',
                'renewal'                   => '01/09/2021',
                'tipo_credito'              => 'Indexado',
                'limite_uvc'                => '204543.112204173',
                'numero_acta'               => 'CAP_20210212',
                'fecha_acta'                => '12/02/2021',
            ],
            [
                'fecha_afiliacion'          => '28/09/2018',
                'gerente_atencion_origen'   => 'Adymar Espinoza',
                'region_origen'             => 'Banca de Empresas',
                'gerente_atencion'          => 'Adymar Espinoza',
                'vicepresidencia'           => 'Corp. Grande Empresa',
                'segmento_cuenta'           => 'Cliente BI',
                't_ci'                      => 'VEN',
                'ci'                        => '4',
                'nombre'                    => 'd',
                'celular'                   => '416',
                'otro_celular'              => '416456',
                'correo'                    => 'd@GMAIL.COM',
                'segmento'                  => 'BLACK',
                'monto'                     => '500',
                'medio_pago'                => 'Afiliación Bi',
                'lc'                        => 'A',
                'ffvv'                      => '',
                'estatus'                   => '1',
                'type'                      => 'RENOVADO',
                'grupo_final'               => 'Grupo 2',
                'expediente'                => 'Completo',
                'contratos_uvc'             => 'Completo',
                'renewal'                   => '01/09/2021',
                'tipo_credito'              => 'Indexado',
                'limite_uvc'                => '204543.112204173',
                'numero_acta'               => 'CAP_20210212',
                'fecha_acta'                => '12/02/2021',
            ]
        ]);
        // DB::unprepared("SET IDENTITY_INSERT {$this->table} OFF");

        return $this;
    }
}