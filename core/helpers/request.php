<?php

class Request
{
    private static $path;
    private static $controlador;
    private static $metodo;

    private static $esAjax;

    private static $parametros;
    private static $method;
    private static $headers;

    public static function analizar() {
        $peticion = $_GET['url'];
        $aPeticion = array_filter( explode('/', $peticion) );

        self::$path = $peticion;
        self::$controlador = "home";
        self::$metodo = "index";

        if( isset($aPeticion[0]) ) self::$controlador = strtolower( $aPeticion[0] );
        if( isset($aPeticion[1]) ) self::$metodo = strtolower( $aPeticion[1] );

        unset($aPeticion[0]);
        unset($aPeticion[1]);
        $aPeticion = array_values($aPeticion);
        self::$parametros = $aPeticion;
        
        self::$method = $_SERVER['REQUEST_METHOD'];
        self::$headers = getallheaders();

        if( isset(self::$headers['Content-Type']) && self::$headers['Content-Type'] == 'application/json' ) {
            Handler::setJson();
            self::$esAjax = TRUE;
        }
    }

    public static function getPath() {
        return self::$path;
    }

    public static function getControlador() {
        return self::$controlador;
    }

    public static function getMetodo() {
        return self::$metodo;
    }

    public static function esAjax() {
        return self::$esAjax;
    }

    public static function getParametros() {
        return self::$parametros;
    }

    public static function input($key = NULL, $required = TRUE) {
        $input_string = file_get_contents("php://input");
        $inputJson = json_decode($input_string);
        if($inputJson == NULL) throw new Exception('El input no es de formato JSON.');

        if($key == NULL) return $inputJson;
        else {
            if( !isset($inputJson->$key) ) {
                if($required) throw new Exception("El parametro '{$key}' no se envio.");
                else return NULL;
            }

            return $inputJson->$key;
        }
    }

    public static function method() {
        return strtoupper( self::$method );
    }

    public static function get($key, $required = TRUE) {
        if( !isset($_GET[$key]) ) {
            if($required) throw new Exception("No se ha recibido el parametro '{$key}' mediante GET.");
            else return NULL;
        }
        else {
            return $_GET[$key];
        }
    }

    public static function post($key, $required = TRUE) {
        if( !isset($_POST[$key]) ) {
            if($required) throw new Exception("No se ha recibido el parametro '{$key}' mediante POST.");
            else return NULL;
        }
        else {
            return $_POST[$key];
        }
    }

    public static function files($key, $required = TRUE) {
        /* Nothing... */
    }
}