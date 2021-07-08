<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        // Validamos la sesion
        Sesion::auth();
        // Validamos los permisos del menu
        if( !Sesion::usuario()->rol->esValido('menu_gestion_contacto_gerencia') ) {
            if(!Request::esAjax()) Response::sin_permisos();
            else throw new Exception('Usted no tiene permisos para acceder a esta pagina.');
        }
    }

    public function index() {
        return Response::view('views/index.html');
    }

    public function api($accion = NULL) {
        Handler::setJson();
        if($accion == NULL) throw new Exception('La acción no se ha enviado.');
        switch(strtolower($accion))
        {
            case 'consultar_datos':
                $cedula = Request::input('cedula', $requerido = TRUE);
                $objPresident = President::where('ci', $cedula)->first();
                if($objPresident == NULL) throw new Exception('Cedula no encontrada.');
                $objSegmento = Segmento::where('CI', $objPresident->ci)->first();

                /**
                 * Seccion 1
                 */

                // Primera fila
                $cedula     = $objPresident->ci;
                $nombre     = $objPresident->nombre;
                $segmento   = ($objSegmento != NULL) ? $objSegmento->Segmento : NULL;

                // Segunda fila
                if(!empty($objPresident->celular) ) {
                    $celular = $objPresident->celular;
                }
                else {
                    $objContacto = Contacto::where('CI', $cedula)->where('Tipo', 'Móvil')->first();
                    $celular = ($objContacto != NULL) ? $objContacto->Nro_Telefono : NULL;
                }
                $objContacto = Contacto::where('CI', $cedula)->where('Tipo', 'Fijo')->first();
                $otro_telefono = ($objContacto != NULL) ? $objContacto->Nro_Telefono : NULL;
                if(!empty($objPresident->correo) ) {
                    $correo = $objPresident->correo;
                } else {
                    $objContacto = Contacto::where('CI', $cedula)->first();
                    $correo = ($objContacto != NULL) ? $objContacto->Email : NULL;
                }

                // Tercera fila
                $gerente_banca_president    = $objPresident->gerente_atencion_origen;
                $gerente_juridico           = $objPresident->gerente_atencion;
                $vpr_juridico               = $objPresident->vicepresidencia;

                /**
                 * Seccion 2
                 */
                $mes_1 = nombre_mes( now('m') - 1 )['nombre_corto'];
                $mes_2 = nombre_mes( now('m') - 2 )['nombre_corto'];
                $mes_3 = nombre_mes( now('m') - 3 )['nombre_corto'];
                $vinculacion_natural = [
                    'dolar'     => [ 'promedio' => NULL, 'mes_1' => NULL, 'mes_2' => NULL, 'mes_3' => NULL ],
                    'euro'      => [ 'promedio' => NULL, 'mes_1' => NULL, 'mes_2' => NULL, 'mes_3' => NULL ],
                    'corriente' => [ 'promedio' => NULL, 'mes_1' => NULL, 'mes_2' => NULL, 'mes_3' => NULL ],
                    'ahorro'    => [ 'promedio' => NULL, 'mes_1' => NULL, 'mes_2' => NULL, 'mes_3' => NULL ],
                ];
                $vinculacion_juridica = $vinculacion_natural;

                // Natural
                $cuenta_natural_corriente = FinancieroNatural::where('CI', $cedula)->whereIn('Moneda', ['Bolívar', 'Bolívares'])->where('Producto', 'Corriente')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_natural_corriente != NULL) {
                    $m1 = bcdiv($cuenta_natural_corriente->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_natural_corriente->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_natural_corriente->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    $vinculacion_natural['corriente']['mes_1']       = $m1;
                    $vinculacion_natural['corriente']['mes_2']       = $m2;
                    $vinculacion_natural['corriente']['mes_3']       = $m3;
                    $vinculacion_natural['corriente']['promedio']    = $p;
                }

                $cuenta_natural_ahorro = FinancieroNatural::where('CI', $cedula)->whereIn('Moneda', ['Bolívar', 'Bolívares'])->where('Producto', 'Ahorro')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_natural_ahorro != NULL) {
                    $m1 = bcdiv($cuenta_natural_ahorro->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_natural_ahorro->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_natural_ahorro->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    $vinculacion_natural['ahorro']['mes_1']       = $m1;
                    $vinculacion_natural['ahorro']['mes_2']       = $m2;
                    $vinculacion_natural['ahorro']['mes_3']       = $m3;
                    $vinculacion_natural['ahorro']['promedio']    = $p;
                }

                $cuenta_natural_dolares = FinancieroNatural::where('CI', $cedula)->whereIn('Moneda', ['Dólar', 'Dólares'])->where('Producto', 'Custodia Dólares')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_natural_dolares != NULL) {
                    $m1 = bcdiv($cuenta_natural_dolares->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_natural_dolares->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_natural_dolares->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    $vinculacion_natural['dolar']['mes_1']       = $m1;
                    $vinculacion_natural['dolar']['mes_2']       = $m2;
                    $vinculacion_natural['dolar']['mes_3']       = $m3;
                    $vinculacion_natural['dolar']['promedio']    = $p;
                }

                $cuenta_natural_euros = FinancieroNatural::where('CI', $cedula)->whereIn('Moneda', ['Euro', 'Euros'])->where('Producto', 'Custodia Euros')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_natural_euros != NULL) {
                    $m1 = bcdiv($cuenta_natural_euros->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_natural_euros->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_natural_euros->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    $vinculacion_natural['euro']['mes_1']       = $m1;
                    $vinculacion_natural['euro']['mes_2']       = $m2;
                    $vinculacion_natural['euro']['mes_3']       = $m3;
                    $vinculacion_natural['euro']['promedio']    = $p;
                }

                // Juridica
                $cuenta_juridica_corriente = FinancieroJuridico::where('CI', $cedula)->whereIn('Moneda', ['Bolívar', 'Bolívares'])->where('Producto', 'Corriente')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_juridica_corriente != NULL) {
                    $m1 = bcdiv($cuenta_juridica_corriente->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_juridica_corriente->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_juridica_corriente->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    $vinculacion_juridica['corriente']['mes_1']       = $m1;
                    $vinculacion_juridica['corriente']['mes_2']       = $m2;
                    $vinculacion_juridica['corriente']['mes_3']       = $m3;
                    $vinculacion_juridica['corriente']['promedio']    = $p;
                }

                $cuenta_juridica_ahorro = FinancieroJuridico::where('CI', $cedula)->whereIn('Moneda', ['Bolívar', 'Bolívares'])->where('Producto', 'Ahorro')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_juridica_ahorro != NULL) {
                    $m1 = bcdiv($cuenta_juridica_ahorro->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_juridica_ahorro->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_juridica_ahorro->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    $vinculacion_juridica['ahorro']['mes_1']       = $m1;
                    $vinculacion_juridica['ahorro']['mes_2']       = $m2;
                    $vinculacion_juridica['ahorro']['mes_3']       = $m3;
                    $vinculacion_juridica['ahorro']['promedio']    = $p;
                }

                $cuenta_juridica_dolares = FinancieroJuridico::where('CI', $cedula)->whereIn('Moneda', ['Dólar', 'Dólares'])->where('Producto', 'Custodia Dólares')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_juridica_dolares != NULL) {
                    $m1 = bcdiv($cuenta_juridica_dolares->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_juridica_dolares->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_juridica_dolares->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    $vinculacion_juridica['dolar']['mes_1']       = $m1;
                    $vinculacion_juridica['dolar']['mes_2']       = $m2;
                    $vinculacion_juridica['dolar']['mes_3']       = $m3;
                    $vinculacion_juridica['dolar']['promedio']    = $p;
                }

                $cuenta_juridica_euros = FinancieroJuridico::where('CI', $cedula)->whereIn('Moneda', ['Euro', 'Euros'])->where('Producto', 'Custodia Euros')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_juridica_euros != NULL) {
                    $m1 = bcdiv($cuenta_juridica_euros->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_juridica_euros->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_juridica_euros->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    $vinculacion_juridica['euro']['mes_1']       = $m1;
                    $vinculacion_juridica['euro']['mes_2']       = $m2;
                    $vinculacion_juridica['euro']['mes_3']       = $m3;
                    $vinculacion_juridica['euro']['promedio']    = $p;
                }

                /**
                 * Seccion 3
                 */

                /**
                  * Respuesta
                  */
                  return Response::json([
                    'seccion_1' => [
                        'cedula'                    => $cedula,
                        'celular'                   => $celular,
                        'gerente_banca_president'   => $gerente_banca_president,
                        'nombre'                    => $nombre,
                        'otro_telefono'             => $otro_telefono,
                        'gerente_juridico'          => $gerente_juridico,
                        'segmento'                  => $segmento,
                        'correo'                    => $correo,
                        'vpr_juridico'              => $vpr_juridico,
                    ],
                    'seccion_2' => [
                        'mes_1' => $mes_1,
                        'mes_2' => $mes_2,
                        'mes_3' => $mes_3,
                        'vinculacion_natural' => $vinculacion_natural,
                        'vinculacion_juridica' => $vinculacion_juridica,
                    ],
                    'seccion_3' => [],
                ]);
            break;

            /**
             * Ninguna de las anteriores
             */
            default: throw new Exception('Acción invalida.');
        }
    }
}