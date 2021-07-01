<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        // Validamos la sesion
        Sesion::auth();
        // Validamos los permisos del menu
        if( !Sesion::usuario()->rol->esValido('menu_roles') ) {
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
            /**
             * DataTable
             */
            case 'datatable':
                // Basic
                $table = 'roles';
                $primaryKey = 'id';
                // Columnas
                $columns = [
                    [ 'db' => 'id', 'dt' => 'id' ],
                    [ 'db' => 'nombre', 'dt' => 'nombre' ],
                ];

                $data = SSP::simple( $_GET, $table, $primaryKey, $columns );
                foreach($data['data'] as $key => $value) {
                    $data['data'][$key]['cant_usuarios'] = Usuario::where('rol_id', $value['id'])->count();         
                }
                
                return json_encode( $data );
            break;

            /**
             * Modificar
             */
            case 'modificar':
                $id = Request::input('id', $obligatorio = TRUE);
                $nombre = Request::input('nombre', $obligatorio = TRUE);
                
                $rol = Rol::find($id);
                if($rol == NULL) throw new Exception("El rol solicitado ({$id}) no existe.");
                if( Rol::where('nombre', $nombre)->where('id', '<>', $rol->id)->count() > 0 ) throw new Exception("El rol <b>{$nombre}</b> ya existe.");
                $rol->nombre = $nombre;
                $rol->save();

                return Response::json([ 'ok' => TRUE ]);
            break;

            /**
             * Permisos
             */
            case 'permisos':
                $id = Request::input('id', $requerido = TRUE);
                $rol = Rol::where('id', $id)->first();
                if($rol == NULL) throw new Exception('El rol solicitado no existe.');

                $permisos = Permiso::select('id', 'slug', 'description')->get();
                foreach($permisos as $key => $permiso) {
                    $permisos[$key]->permitido = $rol->esValido($permiso->slug);
                }

                return Response::json([
                    'rol' => $rol,
                    'permisos' => $permisos
                ]);
            break;

            /**
             * Cambiar permiso
             */
            case 'cambiar-permiso':
                $rol_id = Request::input('rol_id', $requerido = TRUE);
                $slug_permiso = Request::input('slug_permiso', $requerido = TRUE);

                $rol = Rol::where('id', $rol_id)->first();
                if($rol == NULL) throw new Exception('El rol solicitado no existe.');

                $permiso = Permiso::where('slug', $slug_permiso)->first();
                if($permiso == NULL) throw new Exception('El permiso solicitado no existe.');

                DB::beginTransaction();

                $permitido = $rol->esValido($permiso->slug);
                $rol->cambiarPermiso($permiso->id, !$permitido);

                DB::commit();
                
                $permisos = Permiso::select('id', 'slug', 'description')->get();
                foreach($permisos as $key => $permiso) {
                    $permisos[$key]->permitido = $rol->esValido($permiso->slug);
                }

                return Response::json([
                    'rol' => $rol,
                    'permisos' => $permisos
                ]);
            break;
            
            /**
             * Ninguna de las anteriores
             */
            default: throw new Exception('Acción invalida.');
        }
    }
}