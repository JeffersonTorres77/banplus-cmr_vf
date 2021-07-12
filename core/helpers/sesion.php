<?php

class Sesion
{
    private static $key;
    private static $usuario;
    private static $tiempo_sesion = (60*10);

    public static function iniciar() {
        self::$key = config('SESSION.KEY');
        session_start();
    }
    
    public static function crear($usuario) {
        $_SESSION[self::$key."_usuario"] = $usuario;
        $_SESSION[self::$key."_ip"] = IP_CLIENTE;
        $_SESSION[self::$key."_date_start"] = strtotime( now() );
        $_SESSION[self::$key."_date_end"] = strtotime( now() ) + self::$tiempo_sesion;
    }
    
    public static function validar() {
        if(!isset($_SESSION[self::$key."_usuario"])) return FALSE;
        if(!isset($_SESSION[self::$key."_ip"])) return FALSE;
        if(!isset($_SESSION[self::$key."_date_start"])) return FALSE;
        if(!isset($_SESSION[self::$key."_date_end"])) return FALSE;

        $usuario = $_SESSION[self::$key."_usuario"];
        $ip = $_SESSION[self::$key."_ip"];
        $date_start = $_SESSION[self::$key."_date_start"];
        $date_end = $_SESSION[self::$key."_date_end"];

        // Usuario
        self::$usuario = Usuario::where('usuario', $usuario)->first();
        if($ip !== IP_CLIENTE || self::$usuario == NULL || !self::$usuario->activo) {
            self::cerrar();
            return FALSE;
        }

        if(strtotime( now() ) > $date_end) {
            self::cerrar();
            return FALSE;
        }

        $_SESSION[self::$key."_date_end"] = strtotime( now() ) + self::$tiempo_sesion;

        return TRUE;
    }
    
    public static function cerrar() {
        unset( $_SESSION[self::$key."_usuario"] );
        unset( $_SESSION[self::$key."_ip"] );
        unset( $_SESSION[self::$key."_date_start"] );
        unset( $_SESSION[self::$key."_date_end"] );
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