<?php

class Sesion
{
    private static $key;
    private static $usuario;

    public static function iniciar() {
        self::$key = config('SESSION.KEY');
        session_start();
    }
    
    public static function crear($usuario) {
        $_SESSION[self::$key."_usuario"] = $usuario;
        $_SESSION[self::$key."_ip"] = IP_CLIENTE;
        $_SESSION[self::$key."_date_start"] = strtotime( now() );
    }
    
    public static function validar() {
        if(!isset($_SESSION[self::$key."_usuario"])) return FALSE;
        if(!isset($_SESSION[self::$key."_ip"])) return FALSE;
        if(!isset($_SESSION[self::$key."_date_start"])) return FALSE;

        $usuario = $_SESSION[self::$key."_usuario"];
        $ip = $_SESSION[self::$key."_ip"];
        $date_start = $_SESSION[self::$key."_date_start"];

        // Usuario
        self::$usuario = Usuario::where('usuario', $usuario)->first();
        if($ip !== IP_CLIENTE || self::$usuario == NULL || !self::$usuario->activo) {
            self::cerrar();
            return FALSE;
        }

        return TRUE;
    }
    
    public static function cerrar() {
        unset( $_SESSION[self::$key."_usuario"] );
        unset( $_SESSION[self::$key."_ip"] );
        unset( $_SESSION[self::$key."_date_start"] );
    }

    public static function usuario() {
        return self::$usuario;
    }

    public static function auth() {
        if( self::validar() ) return;
        if( Request::esAjax() ) throw new Exception('Sesión no iniciada.');
        $path = Request::getPath();
        $url = BASE_URL."/Login/";
        if($path != "") $url .= "?ir_a={$path}";
        header("location: {$url}");
        exit;
    }

    public static function auth_inverse() {
        if( !self::validar() ) return;
        if( Request::esAjax() ) throw new Exception('Cierre la sesión y vuelva a intentarlo.');
        $url = BASE_URL."/";
        header("location: {$url}");
        exit;
    }
}