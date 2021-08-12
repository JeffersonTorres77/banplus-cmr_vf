<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        Sesion::auth();
    }

    public function index() {
        return Response::view('views/index.html', [
            'usuarios' => Usuario::get(),
            'tipos_gestion' => TipoGestion::get(),
        ]);
    }
    
    public function api($accion = NULL) {
        Handler::setJson();
        if($accion == NULL) throw new Exception('La acción no se ha enviado.');
        switch(strtolower($accion))
        {
            /**
             * Consultar CI
             */
            case 'consultar_ci':
                $ejecutivo_id       = Request::input('ejecutivo_id', $requerido = TRUE);
                $tipo_gestion_id    = Request::input('tipo_gestion_id', $requerido = FALSE);
                $fecha_inicio       = Request::input('fecha_inicio', $requerido = FALSE);
                $fecha_fin          = Request::input('fecha_fin', $requerido = FALSE);

                if( empty($tipo_gestion_id) ) $tipo_gestion_id = NULL;
                if( empty($fecha_inicio) ) $fecha_inicio = NULL;
                if( empty($fecha_fin) ) $fecha_fin = NULL;

                $objEjecutivo = Usuario::find($ejecutivo_id);
                if($objEjecutivo == NULL) throw new Exception('Ejecutivo no encontrado.');

                $tipo_gestion = "Todas";
                if($tipo_gestion_id != NULL) {
                    $objTipoGestion = TipoGestion::find($tipo_gestion_id);
                    if($objTipoGestion == NULL) throw new Exception('Tipo de gestion no encontrado.');
                    $tipo_gestion = $objTipoGestion->nombre;
                }

                $rango_fecha = "No aplica.";
                if($fecha_inicio != NULL && $fecha_fin != NULL) {
                    $rango_fecha = "Desde ". date_to_text($fecha_inicio) ." Hasta ". date_to_text($fecha_fin) ."";
                }

                $gestiones = gestiones_datatable($objEjecutivo->id, $tipo_gestion_id, $fecha_inicio, $fecha_fin);
                $tiempo_total_gestiones = 0;
                $tiempo_promedio_gestiones = 0;
                foreach($gestiones as $gestion) {
                    if($gestion->fecha_cierre == NULL) {
                        $tiempo_total_gestiones = strtotime(now()) - strtotime($gestion->fecha_apertura);
                    }
                    else {
                        $tiempo_total_gestiones = strtotime($gestion->fecha_cierre) - strtotime($gestion->fecha_apertura);
                    }
                }
                $tiempo_promedio_gestiones = bcdiv($tiempo_total_gestiones / count($gestiones), '1', 0);

                return Response::json([
                    'ejecutivo' => $objEjecutivo,
                    'gestiones' => $gestiones,
                    'tipo_gestion' => $tipo_gestion,
                    'rango_fecha' => $rango_fecha,
                    'tiempo_total_gestiones' => timestamp_to_text( $tiempo_total_gestiones ),
                    'tiempo_promedio_gestiones' => timestamp_to_text( $tiempo_promedio_gestiones ),
                ]);
            break;

            /**
             * Ninguna de las anteriores
             */
            default: throw new Exception('Acción invalida.');
        }
    }
}

function gestiones_datatable($usuario_id, $tipo_gestion_id, $fecha_inicio, $fecha_fin) {
    $consulta = Gestion::where('usuario_id', $usuario_id);
    if($tipo_gestion_id != NULL) {
        $consulta->where('tipo_gestion_id', $tipo_gestion_id);
    }
    if($fecha_inicio != NULL && $fecha_fin != NULL) {
        $consulta->where('fecha_gestion', '>=', $fecha_inicio)->where('fecha_gestion', '<=', $fecha_fin);
    }
    
    $gestiones = $consulta->orderBy('created_at', 'desc')->get();
    foreach($gestiones as $key => $gestion) {
        $ejecutivo = Usuario::select('nombres', 'apellidos')->where('id', $gestion->usuario_id)->first();
        if($ejecutivo != NULL) $gestiones[$key]->ejecutivo = "{$ejecutivo->nombres} {$ejecutivo->apellidos}";
        
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

        $gestiones[$key]->area_resolutoria = ($gestion->fecha_asignacion == NULL) ? 'Banca President' : 'Gerencia';

        $gestiones[$key]->es_gerencia = ($gestion->fecha_asignacion != NULL) ? TRUE : FALSE;

        $gestiones[$key]->fecha_apertura = str_replace(' - ', ' a las ', date_to_text( $gestion->fecha_apertura, 'Y/m/d - H:i' ));
        $gestiones[$key]->fecha_cierre = str_replace(' - ', ' a las ', date_to_text( $gestion->fecha_cierre, 'Y/m/d - H:i' ));

        if($gestion->fecha_cierre == NULL) {
            $tiempo_atencion = timestamp_to_text( strtotime(now()) - strtotime($gestion->fecha_apertura) );
        }
        else {
            $tiempo_atencion = timestamp_to_text( strtotime($gestion->fecha_cierre) - strtotime($gestion->fecha_apertura) );
        }
        
        $gestiones[$key]->tiempo_atencion = $tiempo_atencion;
    }
    return $gestiones;
}