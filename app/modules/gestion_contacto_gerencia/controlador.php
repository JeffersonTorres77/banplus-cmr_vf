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
        return Response::view('views/index.html', [
            'tipos_llamadas'    => TipoLlamada::select('id', 'nombre')->get(),
            'tipos_gestion'     => TipoGestion::select('id', 'nombre')->where('ver_gerente', '1')->get(),
            'estatus_gestion'   => EstatusGestion::select('id', 'tipo_id', 'nombre')->where('ver_gerente', '1')->get(),
            'resolucion_comite'   => ResolucionComite::select('id', 'nombre')->get(),
            'membresia_president'   => MembresiaPresident::select('id', 'nombre')->get(),
            'gerentes'          => Gerente::select('id', 'region', 'nombre')->get(),
        ]);
    }

    public function api($accion = NULL) {
        Handler::setJson();
        if($accion == NULL) throw new Exception('La acción no se ha enviado.');
        switch(strtolower($accion))
        {
            case 'consultar_datos':
                $cedula = Request::input('cedula', $requerido = TRUE);
                $objPresident = President::where('ci', $cedula)->first();
                if($objPresident == NULL)
                {
                    // Si no se encuentra en la tabla de president, buscamos en la de clientes temporales
                    $objCliente = ClienteTemporal::where('cedula', $cedula)->first();
                    if($objCliente == NULL) return Response::json([
                        'encontrado' => FALSE,
                        'cedula' => $cedula,
                    ]);

                    $gestiones = gestiones_datatable($cedula);
                    return Response::json([
                        'encontrado' => TRUE,
                        'cedula' => $cedula,
                        'editable' => TRUE,
                        'seccion_1' => [
                            'cedula'                    => $objCliente->cedula,
                            'nombre'                    => $objCliente->nombre,
                            'segmento'                  => $objCliente->segmento,
                            'segmento_membresia'        => $objCliente->segmento_membresia,
                            'grupo_vinculacion'         => $objCliente->grupo_vinculacion,
                            'monto_uvc'                 => bcdiv( floatval($objCliente->monto_uvc), '1', 2 ),
                            'gerente_banca_president'   => $objCliente->gerente_banca_personal,
                            'gerente_juridico'          => $objCliente->gerente_juridico,
                            'vpr_juridico'              => $objCliente->vpr_juridico,
                            'celular'                   => $objCliente->celular,
                            'otro_telefono'             => $objCliente->otro_telefono,
                            'correo'                    => $objCliente->correo,
                        ],
                        /**
                         * Seccion 2
                         * 1 [Verde]    - Cuenta abierda con saldo
                         * 2 [Amarillo] - Cuenta abierta, saldo menor al minimo
                         * 3 [Rojo]     - No tiene el producto
                         */
                        'seccion_2' => [
                            'mes_1' => nombre_mes( now('m') - 1 )['nombre_corto'],
                            'mes_2' => nombre_mes( now('m') - 2 )['nombre_corto'],
                            'mes_3' => nombre_mes( now('m') - 3 )['nombre_corto'],
                            'vinculacion_natural' => [],
                            'vinculacion_juridica' => [],
                        ],
                        'seccion_3' => $gestiones
                    ]);
                }

                $objSegmento = Segmento::where('CI', $objPresident->ci)->first();

                /**
                 * Seccion 1
                 */

                // Primera fila
                $cedula     = $objPresident->ci;
                $nombre     = $objPresident->nombre;
                $segmento   = ($objSegmento != NULL) ? $objSegmento->Segmento : NULL;

                // Segunda fila
                $segmento_membresia = $objPresident->segmento;
                $grupo_vinculacion = $objPresident->grupo_final;
                $monto_uvc = floatval( $objPresident->limite_uvc );

                // Tercera fila
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

                // Cuarta fila
                $gerente_banca_president    = $objPresident->gerente_atencion_origen;
                $gerente_juridico           = $objPresident->gerente_atencion;
                $vpr_juridico               = $objPresident->vicepresidencia;

                /**
                 * Seccion 2
                 */
                // label, mes_1, mes_2, mes_3, promedio
                $vinculacion_natural = [];
                $vinculacion_juridica = [];

                // Natural
                $cuenta_natural_corriente = FinancieroNatural::where('CI', $cedula)->whereIn('Moneda', ['Bolívar', 'Bolívares'])->where('Producto', 'Corriente')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_natural_corriente != NULL) {
                    $m1 = bcdiv($cuenta_natural_corriente->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_natural_corriente->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_natural_corriente->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    array_push($vinculacion_natural, [
                        'key'       => 'corriente',
                        'label'     => 'Cta. Cte',
                        'mes_1'     => $m1,
                        'mes_2'     => $m2,
                        'mes_3'     => $m3,
                        'promedio'  => $p,
                    ]);
                }

                $cuenta_natural_ahorro = FinancieroNatural::where('CI', $cedula)->whereIn('Moneda', ['Bolívar', 'Bolívares'])->where('Producto', 'Ahorro')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_natural_ahorro != NULL) {
                    $m1 = bcdiv($cuenta_natural_ahorro->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_natural_ahorro->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_natural_ahorro->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    array_push($vinculacion_natural, [
                        'key'       => 'ahorro',
                        'label'     => 'Cta. Ahorro',
                        'mes_1'     => $m1,
                        'mes_2'     => $m2,
                        'mes_3'     => $m3,
                        'promedio'  => $p,
                    ]);
                }

                $cuenta_natural_dolares = FinancieroNatural::where('CI', $cedula)->whereIn('Moneda', ['Dólar', 'Dólares'])->where('Producto', 'Custodia Dólares')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_natural_dolares != NULL) {
                    $m1 = bcdiv($cuenta_natural_dolares->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_natural_dolares->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_natural_dolares->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    array_push($vinculacion_natural, [
                        'key'       => 'dolar',
                        'label'     => 'Cust. $',
                        'mes_1'     => $m1,
                        'mes_2'     => $m2,
                        'mes_3'     => $m3,
                        'promedio'  => $p,
                    ]);
                }

                $cuenta_natural_euros = FinancieroNatural::where('CI', $cedula)->whereIn('Moneda', ['Euro', 'Euros'])->where('Producto', 'Custodia Euros')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_natural_euros != NULL) {
                    $m1 = bcdiv($cuenta_natural_euros->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_natural_euros->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_natural_euros->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    array_push($vinculacion_natural, [
                        'key'       => 'euro',
                        'label'     => 'Cust. Є',
                        'mes_1'     => $m1,
                        'mes_2'     => $m2,
                        'mes_3'     => $m3,
                        'promedio'  => $p,
                    ]);
                }

                // Juridica
                $cuenta_juridica_corriente = FinancieroJuridico::where('CI', $cedula)->whereIn('Moneda', ['Bolívar', 'Bolívares'])->where('Producto', 'Corriente')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_juridica_corriente != NULL) {
                    $m1 = bcdiv($cuenta_juridica_corriente->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_juridica_corriente->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_juridica_corriente->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    array_push($vinculacion_juridica, [
                        'key'       => 'corriente',
                        'label'     => 'Cta. Cte',
                        'mes_1'     => $m1,
                        'mes_2'     => $m2,
                        'mes_3'     => $m3,
                        'promedio'  => $p,
                    ]);
                }

                $cuenta_juridica_ahorro = FinancieroJuridico::where('CI', $cedula)->whereIn('Moneda', ['Bolívar', 'Bolívares'])->where('Producto', 'Ahorro')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_juridica_ahorro != NULL) {
                    $m1 = bcdiv($cuenta_juridica_ahorro->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_juridica_ahorro->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_juridica_ahorro->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    array_push($vinculacion_juridica, [
                        'key'       => 'ahorro',
                        'label'     => 'Cta. Ahorro',
                        'mes_1'     => $m1,
                        'mes_2'     => $m2,
                        'mes_3'     => $m3,
                        'promedio'  => $p,
                    ]);
                }

                $cuenta_juridica_dolares = FinancieroJuridico::where('CI', $cedula)->whereIn('Moneda', ['Dólar', 'Dólares'])->where('Producto', 'Custodia Dólares')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_juridica_dolares != NULL) {
                    $m1 = bcdiv($cuenta_juridica_dolares->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_juridica_dolares->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_juridica_dolares->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    array_push($vinculacion_juridica, [
                        'key'       => 'dolar',
                        'label'     => 'Cust. $',
                        'mes_1'     => $m1,
                        'mes_2'     => $m2,
                        'mes_3'     => $m3,
                        'promedio'  => $p,
                    ]);
                }

                $cuenta_juridica_euros = FinancieroJuridico::where('CI', $cedula)->whereIn('Moneda', ['Euro', 'Euros'])->where('Producto', 'Custodia Euros')->orderBy('Promedio_Mes_1', 'DESC')->first();
                if($cuenta_juridica_euros != NULL) {
                    $m1 = bcdiv($cuenta_juridica_euros->Promedio_Mes_1, '1', 2);
                    $m2 = bcdiv($cuenta_juridica_euros->Promedio_Mes_2, '1', 2);
                    $m3 = bcdiv($cuenta_juridica_euros->Promedio_Mes_3, '1', 2);
                    $p = bcdiv(($m1 + $m2 + $m3) / 3, '1', 2);
                    array_push($vinculacion_juridica, [
                        'key'       => 'euro',
                        'label'     => 'Cust. Є',
                        'mes_1'     => $m1,
                        'mes_2'     => $m2,
                        'mes_3'     => $m3,
                        'promedio'  => $p,
                    ]);
                }

                /**
                 * Seccion 3
                 */
                $gestiones = gestiones_datatable($cedula);

                /**
                 * Borramos el cliente recien encontrado del area de clientes temporales
                 */
                if(ClienteTemporal::where('cedula', $objPresident->ci)->count() > 0) {
                    DB::beginTransaction();
                    ClienteTemporal::where('cedula', $objPresident->ci)->delete();
                    DB::commit();
                }

                /**
                  * Respuesta
                  */
                  return Response::json([
                    'encontrado' => TRUE,
                    'cedula' => $cedula,
                    'editable' => FALSE,
                    'seccion_1' => [
                        'cedula'                    => $cedula,
                        'nombre'                    => $nombre,
                        'segmento'                  => $segmento,
                        'segmento_membresia'        => $segmento_membresia,
                        'grupo_vinculacion'         => $grupo_vinculacion,
                        'monto_uvc'                 => $monto_uvc,
                        'gerente_banca_president'   => $gerente_banca_president,
                        'gerente_juridico'          => $gerente_juridico,
                        'vpr_juridico'              => $vpr_juridico,
                        'celular'                   => $celular,
                        'otro_telefono'             => $otro_telefono,
                        'correo'                    => $correo,
                    ],
                    'seccion_2' => [
                        'mes_1' => nombre_mes( now('m') - 1 )['nombre_corto'],
                        'mes_2' => nombre_mes( now('m') - 2 )['nombre_corto'],
                        'mes_3' => nombre_mes( now('m') - 3 )['nombre_corto'],
                        'vinculacion_natural' => $vinculacion_natural,
                        'vinculacion_juridica' => $vinculacion_juridica,
                    ],
                    'seccion_3' => $gestiones
                ]);
            break;

            /**
             * Registrar nueva gestion
             */
            case 'registrar_gestion':
                // Tomamos parametros
                $fecha_asignacion = Request::input('fecha_asignacion', $requerido = TRUE);
                $cedula = Request::input('cedula', $requerido = TRUE);
                $tipo_llamada_id = Request::input('tipo_llamada', $requerido = TRUE);
                $tipo_gestion_id = Request::input('tipo_gestion', $requerido = TRUE);
                $estatus_gestion_id = Request::input('estatus_gestion', $requerido = TRUE);
                $comentario = Request::input('comentario', $requerido = TRUE);
                $resolucion_comite_id = Request::input('resolucion_comite', $requerido = TRUE);
                $fecha_comite = Request::input('fecha_comite', $requerido = TRUE);
                $membresia_president_id = Request::input('membresia_president', $requerido = TRUE);
                $fecha_pago = Request::input('fecha_pago', $requerido = TRUE);
                
                // Validamos parametros
                $objPresident = President::where('ci', $cedula)->first();
                if($objPresident != NULL) {
                    $cedula = $objPresident->ci;
                }
                else {
                    $objCliente = ClienteTemporal::where('cedula', $cedula)->first();
                    if($objCliente == NULL) throw new Exception('Cliente no encontrado.');
                    $cedula = $objCliente->cedula;
                }

                $objTipo_llamada = TipoLlamada::find($tipo_llamada_id);
                if($objTipo_llamada == NULL) throw new Exception('Tipo de llamada invalida.');

                $objEstatus_gestion = EstatusGestion::find($estatus_gestion_id);
                if($objEstatus_gestion == NULL) throw new Exception('Estatus de gestión invalido.');

                $objResolucion_comite = EstatusGestion::find($resolucion_comite_id);
                if($objResolucion_comite == NULL) throw new Exception('Resolucion de comite invalida.');

                $objMembresia_president = EstatusGestion::find($membresia_president_id);
                if($objMembresia_president == NULL) throw new Exception('Membresia President invalida.');

                if( empty($fecha_asignacion) ) throw new Exception('La fecha de asignación no puede estar vacia.');
                if( empty($fecha_comite) ) throw new Exception('La fecha de comite no puede estar vacia.');
                if( empty($fecha_pago) ) throw new Exception('La fecha de pago no puede estar vacia.');

                // Valores por defecto
                $fecha_gestion = now('Y-m-d');
                $objEjecutivo = Sesion::usuario();

                // Registramos datos
                DB::beginTransaction();

                $objGestion = new Gestion;
                $objGestion->fecha_asignacion           = $fecha_asignacion;
                $objGestion->fecha_gestion              = $fecha_gestion;
                $objGestion->ci                         = $cedula;
                $objGestion->usuario_id                 = $objEjecutivo->id;
                $objGestion->tipo_llamada_id            = $objTipo_llamada->id;
                $objGestion->tipo_gestion_id            = $objEstatus_gestion->tipo_id;
                $objGestion->estatus_gestion_id         = $objEstatus_gestion->id;
                $objGestion->comentario                 = $comentario;
                $objGestion->resolucion_comite_id       = $objResolucion_comite->id;
                $objGestion->fecha_comite               = $fecha_comite;
                $objGestion->membresia_president_id     = $objMembresia_president->id;
                $objGestion->fecha_pago                 = $fecha_pago;
                $objGestion->save();

                DB::commit();

                $gestiones = gestiones_datatable($cedula);

                // Retornamos
                return Response::json([
                    'gestiones' => $gestiones
                ]);
            break;

            /**
             * Modificar Comentario
             */
            case 'modificar-comentario':
                $id = Request::input('id', $requerido = TRUE);
                $comentario = Request::input('comentario', $requerido = TRUE);

                $objGestion = Gestion::find($id);
                if($objGestion == NULL) throw new Exception('Gestión invalida.');
                if($objGestion->usuario_id != Sesion::usuario()->id) throw new Exception('No tiene permisos de modificar este comentario.');

                if( empty($comentario) ) throw new Exception('El comentario no puede estar vacio.');
                
                DB::beginTransaction();
                $objGestion->comentario = $comentario;
                $objGestion->save();
                DB::commit();

                $gestiones = gestiones_datatable($objGestion->ci);

                // Retornamos
                return Response::json([
                    'gestiones' => $gestiones
                ]);
            break;

            /**
             * Registrar cliente temporal
             */
            case 'registrar-cliente':
                $cedula                     = Request::input('cedula', $requerido = TRUE);
                $nombre                     = Request::input('nombre', $requerido = TRUE);
                $segmento                   = Request::input('segmento', $requerido = TRUE);
                $segmento_membresia         = Request::input('segmento_membresia', $requerido = TRUE);
                $grupo_vinculacion          = Request::input('grupo_vinculacion', $requerido = TRUE);
                $monto_uvc                  = Request::input('monto_uvc', $requerido = TRUE);
                $gerente_banca_persona      = Request::input('gerente_banca_persona', $requerido = TRUE);
                $gerente_juridico           = Request::input('gerente_juridico', $requerido = TRUE);
                $vpr_juridico               = Request::input('vpr_juridico', $requerido = TRUE);
                $celular                    = Request::input('celular', $requerido = TRUE);
                $otro_telefono              = Request::input('otro_telefono', $requerido = TRUE);
                $correo                     = Request::input('correo', $requerido = TRUE);

                if( !is_numeric($monto_uvc) ) throw new Exception("El campo 'Monto UVC' debe ser numerico.");
                $monto_uvc = floatval( $monto_uvc );
                $monto_uvc = bcdiv($monto_uvc, '1', 2);

                if( President::where('ci', $cedula)->count() > 0 ) throw new Exception('La cedula ya esta registrada.');
                if( ClienteTemporal::where('cedula', $cedula)->count() > 0 ) throw new Exception('La cedula ya esta registrada.');

                DB::beginTransaction();
                
                $objCliente = new ClienteTemporal;
                $objCliente->cedula = $cedula;
                $objCliente->nombre = $nombre;
                $objCliente->segmento = $segmento;
                $objCliente->segmento_membresia = $segmento_membresia;
                $objCliente->grupo_vinculacion = $grupo_vinculacion;
                $objCliente->monto_uvc = $monto_uvc;
                $objCliente->gerente_banca_persona = $gerente_banca_persona;
                $objCliente->gerente_juridico = $gerente_juridico;
                $objCliente->vpr_juridico = $vpr_juridico;
                $objCliente->celular = $celular;
                $objCliente->otro_telefono = $otro_telefono;
                $objCliente->correo = $correo;
                $objCliente->save();

                DB::commit();

                // Retornamos
                return Response::json([
                    'ok' => TRUE,
                    'cedula' => $objCliente->cedula
                ]);
            break;

            /**
             * Modificar cliente temporal
             */
            case 'modificar-cliente':
                $cedula                     = Request::input('cedula', $requerido = TRUE);
                $nombre                     = Request::input('nombre', $requerido = TRUE);
                $segmento                   = Request::input('segmento', $requerido = TRUE);
                $segmento_membresia         = Request::input('segmento_membresia', $requerido = TRUE);
                $grupo_vinculacion          = Request::input('grupo_vinculacion', $requerido = TRUE);
                $monto_uvc                  = Request::input('monto_uvc', $requerido = TRUE);
                $gerente_banca_persona      = Request::input('gerente_banca_persona', $requerido = TRUE);
                $gerente_juridico           = Request::input('gerente_juridico', $requerido = TRUE);
                $vpr_juridico               = Request::input('vpr_juridico', $requerido = TRUE);
                $celular                    = Request::input('celular', $requerido = TRUE);
                $otro_telefono              = Request::input('otro_telefono', $requerido = TRUE);
                $correo                     = Request::input('correo', $requerido = TRUE);
                
                if( !is_numeric($monto_uvc) ) throw new Exception("El campo 'Monto UVC' debe ser numerico.");
                $monto_uvc = floatval( $monto_uvc );
                $monto_uvc = bcdiv($monto_uvc, '1', 2);

                $objCliente = ClienteTemporal::where('cedula', $cedula)->first();
                if($objCliente == NULL) throw new Exception('Cedula no encontrada entre los datos de los clientes temporales');

                DB::beginTransaction();

                $objCliente->nombre = $nombre;
                $objCliente->segmento = $segmento;
                $objCliente->segmento_membresia = $segmento_membresia;
                $objCliente->grupo_vinculacion = $grupo_vinculacion;
                $objCliente->monto_uvc = $monto_uvc;
                $objCliente->gerente_banca_persona = $gerente_banca_persona;
                $objCliente->gerente_juridico = $gerente_juridico;
                $objCliente->vpr_juridico = $vpr_juridico;
                $objCliente->celular = $celular;
                $objCliente->otro_telefono = $otro_telefono;
                $objCliente->correo = $correo;
                $objCliente->save();

                DB::commit();

                // Retornamos
                return Response::json([
                    'ok' => TRUE,
                    'cedula' => $objCliente->cedula
                ]);
            break;

            /**
             * Eliminar gestiones
             */
            case 'eliminar-gestion':
                $gestion_id = Request::input('gestion_id', $requerido = TRUE);
                $objGestion = Gestion::find($gestion_id);

                if( !Sesion::usuario()->rol->esValido('gestion_eliminar') ) {
                    throw new Exception('No tiene permisos para realizar esta operación.');
                }

                DB::beginTransaction();
                $objGestion->delete();
                DB::commit();
                
                $gestiones = gestiones_datatable($objGestion->ci);

                // Retornamos
                return Response::json([
                    'gestiones' => $gestiones
                ]);
            break;

            /**
             * Ninguna de las anteriores
             */
            default: throw new Exception('Acción invalida.');
        }
    }
}

function gestiones_datatable($ci) {
    $gestiones = Gestion::where('ci', $ci)->orderBy('created_at', 'DESC')->get();
    foreach($gestiones as $key => $gestion) {
        $usuario = Usuario::select('nombres', 'apellidos')->where('id', $gestion->usuario_id)->first();
        if($usuario != NULL) $gestiones[$key]->ejecutivo = "{$usuario->nombres} {$usuario->apellidos}";
        
        $tipo_llamada = TipoLLamada::select('nombre')->where('id', $gestion->tipo_llamada_id)->first();
        if($tipo_llamada != NULL) $gestiones[$key]->tipo_llamada = $tipo_llamada->nombre;
        
        $tipo_gestion = TipoGestion::select('nombre')->where('id', $gestion->tipo_gestion_id)->first();
        if($tipo_gestion != NULL) $gestiones[$key]->tipo_gestion = $tipo_gestion->nombre;
        
        $estatus_gestion = EstatusGestion::select('nombre')->where('id', $gestion->estatus_gestion_id)->first();
        if($estatus_gestion != NULL) $gestiones[$key]->estatus_gestion = $estatus_gestion->nombre;
        
        $resolucion_comite = ResolucionComite::select('nombre')->where('id', $gestion->resolucion_comite_id)->first();
        if($resolucion_comite != NULL) $gestiones[$key]->resolucion_comite = $resolucion_comite->nombre;
        
        $membresia_president = MembresiaPresident::select('nombre')->where('id', $gestion->membresia_president_id)->first();
        if($membresia_president != NULL) $gestiones[$key]->membresia_president = $membresia_president->nombre;
    }
    return $gestiones;
}