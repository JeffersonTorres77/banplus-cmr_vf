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

    public function nueva($cedula = NULL) {
        if($cedula == NULL) Response::error_404();
        $objPresident = President::where('ci', $cedula)->first()->toArray();
        if($objPresident == NULL) Response::error_404();
        return Response::view('views/nueva.html', $objPresident);
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
                 * 1 [Verde]    - Cuenta abierda con saldo
                 * 2 [Amarillo] - Cuenta abierta, saldo menor al minimo
                 * 3 [Rojo]     - No tiene el producto
                 */
                $monto_minimo_bolivares = 10000000;
                $monto_minimo_divisas = 10;

                // Cuentas en Bs (corriente o ahorro)
                $cuenta_natural_bs = FinancieroNatural::where('CI', $cedula)->whereIn('Moneda', ['Bolívar', 'Bolívares'])->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_natural_bs != NULL && $cuenta_natural_bs->Promedio_Mes_1 >= $monto_minimo_bolivares) {
                    $status_natural_bs = 1;
                }
                elseif($cuenta_natural_bs != NULL && $cuenta_natural_bs->Promedio_Mes_1 < $monto_minimo_bolivares) {
                    $status_natural_bs = 2;
                }
                else {
                    $status_natural_bs = 3;
                }
                
                //  Cuentas en Divisas (dólares o euros)
                $cuenta_natural_divisas = FinancieroNatural::where('CI', $cedula)->whereIn('Moneda', ['Dólar', 'Dólares', 'Euro', 'Euros'])->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_natural_divisas != NULL && $cuenta_natural_divisas->Promedio_Mes_1 >= $monto_minimo_divisas) {
                    $status_natural_divisas = 1;
                }
                elseif($cuenta_natural_divisas != NULL && $cuenta_natural_divisas->Promedio_Mes_1 < $monto_minimo_divisas) {
                    $status_natural_divisas = 2;
                }
                else {
                    $status_natural_divisas = 3;
                }

                // BI
                $status_natural_bi = 3;

                // Cuentas en Bs (corriente o ahorro)
                $cuenta_juridica_bs = FinancieroJuridico::where('CI', $cedula)->whereIn('Moneda', ['Bolívar', 'Bolívares'])->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_juridica_bs != NULL && $cuenta_juridica_bs->Promedio_Mes_1 >= $monto_minimo_bolivares) {
                    $status_juridico_bs = 1;
                }
                elseif($cuenta_juridica_bs != NULL && $cuenta_juridica_bs->Promedio_Mes_1 < $monto_minimo_bolivares) {
                    $status_juridico_bs = 2;
                }
                else {
                    $status_juridico_bs = 3;
                }

                //  Cuentas en Divisas (dólares o euros)
                $cuenta_juridica_divisas = FinancieroJuridico::where('CI', $cedula)->whereIn('Moneda', ['Dólar', 'Dólares', 'Euro', 'Euros'])->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_juridica_divisas != NULL && $cuenta_juridica_divisas->Promedio_Mes_1 >= $monto_minimo_divisas) {
                    $status_juridico_divisas = 1;
                }
                elseif($cuenta_juridica_divisas != NULL && $cuenta_juridica_divisas->Promedio_Mes_1 < $monto_minimo_divisas) {
                    $status_juridico_divisas = 2;
                }
                else {
                    $status_juridico_divisas = 3;
                }

                // BI
                $status_juridico_bi = 3;

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
                        'natural-bs'        => $status_natural_bs,
                        'natural-divisas'   => $status_natural_divisas,
                        'natural-bi'        => $status_natural_bi,
                        'juridica-bs'       => $status_juridico_bs,
                        'juridica-divisas'  => $status_juridico_divisas,
                        'juridica-bi'       => $status_juridico_bi,
                    ],
                ]);
            break;

            /**
             * Ninguna de las anteriores
             */
            default: throw new Exception('Acción invalida.');
        }
    }
}