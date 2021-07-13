<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        // Validamos la sesion
        Sesion::auth();
        // Validamos los permisos del menu
        if( !Sesion::usuario()->rol->esValido('menu_gestion_contacto') ) {
            if(!Request::esAjax()) Response::sin_permisos();
            else throw new Exception('Usted no tiene permisos para acceder a esta pagina.');
        }
    }

    public function index() {
        return Response::view('views/index.html', [
            'tipos_llamadas'    => TipoLlamada::select('id', 'nombre')->get(),
            'tipos_gestion'     => TipoGestion::select('id', 'nombre')->get(),
            'estatus_gestion'   => EstatusGestion::select('id', 'tipo_id', 'nombre')->get(),
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
                            'celular'                   => $objCliente->celular,
                            'gerente_banca_president'   => $objCliente->gerente_banca_personal,
                            'nombre'                    => $objCliente->nombre,
                            'otro_telefono'             => $objCliente->otro_telefono,
                            'gerente_juridico'          => $objCliente->gerente_juridico,
                            'segmento'                  => $objCliente->segmento,
                            'correo'                    => $objCliente->correo,
                            'vpr_juridico'              => $objCliente->vpr_juridico,
                        ],
                        /**
                         * Seccion 2
                         * 1 [Verde]    - Cuenta abierda con saldo
                         * 2 [Amarillo] - Cuenta abierta, saldo menor al minimo
                         * 3 [Rojo]     - No tiene el producto
                         */
                        'seccion_2' => [
                            'natural-bs'        => 3,
                            'natural-divisas'   => 3,
                            'natural-bi'        => 3,
                            'juridica-bs'       => 3,
                            'juridica-divisas'  => 3,
                            'juridica-bi'       => 3,
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
                    'seccion_3' => $gestiones
                ]);
            break;

            /**
             * Registrar nueva gestion
             */
            case 'registrar_gestion':
                // Tomamos parametros
                $cedula = Request::input('cedula', $requerido = TRUE);
                $tipo_llamada_id = Request::input('tipo_llamada', $requerido = TRUE);
                $tipo_gestion_id = Request::input('tipo_gestion', $requerido = TRUE);
                $estatus_gestion_id = Request::input('estatus_gestion', $requerido = TRUE);
                $comentario = Request::input('comentario', $requerido = TRUE);
                
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

                // Valores por defecto
                $fecha_gestion = now('Y-m-d');
                $objEjecutivo = Sesion::usuario();

                // Registramos datos
                DB::beginTransaction();

                $objGestion = new Gestion;
                $objGestion->fecha_asignacion           = NULL;
                $objGestion->fecha_gestion              = $fecha_gestion;
                $objGestion->ci                         = $cedula;
                $objGestion->usuario_id                 = $objEjecutivo->id;
                $objGestion->tipo_llamada_id            = $objTipo_llamada->id;
                $objGestion->tipo_gestion_id            = $objEstatus_gestion->tipo_id;
                $objGestion->estatus_gestion_id         = $objEstatus_gestion->id;
                $objGestion->comentario                 = $comentario;
                $objGestion->resolucion_comite_id       = NULL;
                $objGestion->fecha_comite               = NULL;
                $objGestion->membresia_president_id     = NULL;
                $objGestion->fecha_pago                 = NULL;
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
                $cedula                    = Request::input('cedula', $requerido = TRUE);
                $celular                   = Request::input('celular', $requerido = TRUE);
                $gerente_banca_persona     = Request::input('gerente_banca_persona', $requerido = TRUE);
                $nombre                    = Request::input('nombre', $requerido = TRUE);
                $otro_telefono             = Request::input('otro_telefono', $requerido = TRUE);
                $gerente_juridico          = Request::input('gerente_juridico', $requerido = TRUE);
                $segmento                  = Request::input('segmento', $requerido = TRUE);
                $correo                    = Request::input('correo', $requerido = TRUE);
                $vpr_juridico              = Request::input('vpr_juridico', $requerido = TRUE);

                if( President::where('ci', $cedula)->count() > 0 ) throw new Exception('La cedula ya esta registrada.');
                if( ClienteTemporal::where('cedula', $cedula)->count() > 0 ) throw new Exception('La cedula ya esta registrada.');

                DB::beginTransaction();
                $objCliente = new ClienteTemporal;
                $objCliente->cedula = $cedula;
                $objCliente->nombre = $nombre;
                $objCliente->segmento = $segmento;
                $objCliente->celular = $celular;
                $objCliente->otro_telefono = $otro_telefono;
                $objCliente->gerente_banca_persona = $gerente_banca_persona;
                $objCliente->gerente_juridico = $gerente_juridico;
                $objCliente->correo = $correo;
                $objCliente->vpr_juridico = $vpr_juridico;
                $objCliente->save();
                DB::commit();

                // Retornamos
                return Response::json([
                    'ok' => TRUE,
                    'cedula' => $objCliente->cedula
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