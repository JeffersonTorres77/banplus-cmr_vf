<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        // Validamos la sesion
        Sesion::auth();
        // Validamos los permisos del menu
        if( !Sesion::usuario()->rol->esValido('menu_usuarios') ) {
            if(!Request::esAjax()) Response::sin_permisos();
            else throw new Exception('Usted no tiene permisos para acceder a esta pagina.');
        }
    }

    public function index() {
        return Response::view('views/index.html');
    }

    public function ver($usuario) {
        $objUsuario = Usuario::where('usuario', $usuario)->first();
        if($objUsuario == NULL) throw new Exception('El usuario solicitado no existe.');
        return Response::view('views/ver.html', [
            'usuario' => $objUsuario,
            'roles' => Rol::select('id', 'nombre')->get()
        ]);
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
                $table = 'usuarios';
                $primaryKey = 'id';
                // Columnas
                $columns = [
                    [ 'db' => 'id', 'dt' => 'id' ],
                    [ 'db' => 'usuario', 'dt' => 'usuario' ],
                    [ 'db' => 'nombres', 'dt' => 'nombres' ],
                    [ 'db' => 'apellidos', 'dt' => 'apellidos' ],
                    [
                        'db' => 'rol_id', 'dt' => 'rol',
                        'formatter' => function($d, $row) {
                            return Rol::find($d);
                        }
                    ],
                    [ 'db' => 'activo', 'dt' => 'activo' ],
                    [ 'db' => 'validar_red', 'dt' => 'validar_red' ],
                ];

                $data = SSP::simple( $_GET, $table, $primaryKey, $columns );
                return json_encode( $data );
            break;

            /**
             * Registrar usuarios regulares
             */
            case 'registrar-regular':
                $usuario        = Request::input('usuario', $requerido = TRUE);
                $nombres        = Request::input('nombres', $requerido = TRUE);
                $apellidos      = Request::input('apellidos', $requerido = TRUE);
                $correo         = Request::input('correo', $requerido = TRUE);
                $cargo          = Request::input('cargo', $requerido = TRUE);
                $departamento   = Request::input('departamento', $requerido = TRUE);
                $clave          = Request::input('clave', $requerido = TRUE);

                if( Usuario::where('usuario', $usuario)->count() > 0 ) throw new Exception('El usuario ya esta registrado.');
                if( empty($nombres) )       throw new Exception("El campo 'Nombres' no puede estar vacio.");
                if( empty($apellidos) )     throw new Exception("El campo 'Apellidos' no puede estar vacio.");
                if( empty($correo) )        throw new Exception("El campo 'Correo' no puede estar vacio.");
                if( empty($cargo) )         $cargo = NULL;
                if( empty($departamento) )  $departamento = NULL;
                if( empty($clave) )         throw new Exception("El campo 'clave' no puede estar vacio.");

                DB::beginTransaction();

                $objUsuario = new Usuario;
                $objUsuario->rol_id = 2;
                $objUsuario->usuario = $usuario;
                $objUsuario->nombres = $nombres;
                $objUsuario->apellidos = $apellidos;
                $objUsuario->correo = $correo;
                $objUsuario->cargo = $cargo;
                $objUsuario->departamento = $departamento;
                $objUsuario->validar_red = FALSE;
                $objUsuario->clave = $clave;
                $objUsuario->activo = TRUE;
                $objUsuario->save();

                DB::commit();

                return Response::json(['ok' => TRUE]);
            break;

            /**
             * Registrar usuarios de red
             */
            case 'registrar-red':
                $nuevo_usuario        = Request::input('nuevo_usuario', $requerido = TRUE);
                $login_usuario  = Request::input('usuario', $requerido = TRUE);
                $login_clave    = Request::input('clave', $requerido = TRUE);

                if( Usuario::where('usuario', $nuevo_usuario)->count() > 0 ) throw new Exception('El usuario ya esta registrado.');

                $ldap = new LDAP;
                if( !$ldap->conectar($login_usuario, $login_clave) ) throw new Exception("Usuario o contraseña incorrectas.");
                $datos = $ldap->consultar_usuario($nuevo_usuario);

                DB::beginTransaction();

                $objUsuario = new Usuario;
                $objUsuario->rol_id = 2;
                $objUsuario->usuario = $datos['usuario'];
                $objUsuario->nombres = $datos['nombres'];
                $objUsuario->apellidos = $datos['apellidos'];
                $objUsuario->correo = $datos['correo'];
                $objUsuario->cargo = $datos['cargo'];
                $objUsuario->departamento = $datos['departamento'];
                $objUsuario->validar_red = TRUE;
                $objUsuario->clave = NULL;
                $objUsuario->activo = TRUE;
                $objUsuario->save();

                DB::commit();

                return Response::json(['ok' => TRUE]);
            break;

            /**
             * Modificar Datos
             */
            case 'modificar-datos':
                $id = Request::input('id', $requerido = TRUE);
                $objUsuario = Usuario::find($id);
                if($objUsuario == NULL) throw new Exception('Usuario no encontrado.');

                if($objUsuario->validar_red) {
                    $usuario    = Request::input('usuario', $requerido = TRUE);
                    $clave      = Request::input('clave', $requerido = TRUE);

                    $ldap = new LDAP;
                    if( !$ldap->conectar($usuario, $clave) ) throw new Exception("Usuario o contraseña incorrectas.");
                    $datos = $ldap->consultar_usuario($objUsuario->usuario);
        
                    $nombres        = $datos['nombres'];
                    $apellidos      = $datos['apellidos'];
                    $correo         = $datos['correo'];
                    $cargo          = $datos['cargo'];
                    $departamento   = $datos['departamento'];
                }
                else {
                    $nombres        = Request::input('nombres', $requerido = TRUE);
                    $apellidos      = Request::input('apellidos', $requerido = TRUE);
                    $correo         = Request::input('correo', $requerido = TRUE);
                    $cargo          = Request::input('cargo', $requerido = TRUE);
                    $departamento   = Request::input('departamento', $requerido = TRUE);
    
                    if( empty($nombres) )       throw new Exception("El campo 'nombres' no puede estar vacio.");
                    if( empty($apellidos) )     throw new Exception("El campo 'apellidos' no puede estar vacio.");
                    if( empty($correo) )        throw new Exception("El campo 'correo' no puede estar vacio.");
                    if( empty($cargo) )         $cargo = NULL;
                    if( empty($departamento) )  $departamento = NULL;
                }
                
                DB::beginTransaction();

                $objUsuario->nombres        = $nombres;
                $objUsuario->apellidos      = $apellidos;
                $objUsuario->correo         = $correo;
                $objUsuario->cargo          = $cargo;
                $objUsuario->departamento   = $departamento;
                $objUsuario->save();

                DB::commit();

                return Response::json($objUsuario);
            break;

            /**
             * Modificar Otros Datos
             */
            case 'modificar-otros-datos':
                $id = Request::input('id', $requerido = TRUE);
                $objUsuario = Usuario::find($id);
                if($objUsuario == NULL) throw new Exception('Usuario no encontrado.');
                
                $rol_id = Request::input('rol_id', $requerido = TRUE);
                $activo = Request::input('activo', $requerido = TRUE);
                
                $rol = Rol::find($rol_id);
                if($rol == NULL) throw new Exception('Rol no encontrado.');
                $activo = boolval($activo);
                
                DB::beginTransaction();

                $clave = Request::input('clave', $requerido = FALSE);
                if(!$objUsuario->validar_red && !empty($clave)) $objUsuario->clave = $clave;

                $objUsuario->rol_id = $rol->id;
                $objUsuario->activo = $activo;
                $objUsuario->save();

                DB::commit();

                return Response::json($objUsuario);
            break;

            /**
             * Eliminar
             */
            case 'eliminar':
                throw new Exception('Eliminar');
            break;
            
            /**
             * Ninguna de las anteriores
             */
            default: throw new Exception('Acción invalida.');
        }
    }
}