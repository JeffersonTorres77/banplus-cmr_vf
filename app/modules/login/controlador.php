<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        Sesion::auth_inverse();
    }

    public function index() {
        $ir_a = Request::get('ir_a', FALSE);
        return Response::view('views/index.html', [
            'ir_a' => $ir_a
        ]);
    }

    public function acceder() {
        $user = Request::input('user');
        $pass = Request::input('pass');
        
        $usuario = Usuario::where('usuario', $user)->first();
        if($usuario == NULL) throw new Exception('Usuario incorrecto.');

        if( $usuario->validar_red ) {
            $ldap = new LDAP;
            if( !$ldap->conectar($user, $pass) ) throw new Exception("Contraseña incorrecta.");
        }
        else {
            if( $usuario->clave !== $pass ) throw new Exception("Contraseña incorrecta.");
        }
        if( $usuario->activo == FALSE ) throw new Exception('Usuario no activo.');
        Sesion::crear($usuario->usuario);
        return Response::json(['login' => TRUE]);
    }
}