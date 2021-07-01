<?php

class Handler
{
    private static $setAjax = FALSE;

    public static function setJson($value = TRUE) {
        self::$setAjax = $value;
    }

    public static function exception($e) {
        $numero = $e->getCode();
        $mensaje = $e->getMessage();
        $archivo = $e->getFile();
        $linea = $e->getLine();
        $trazas = $e->getTrace();

        if(self::$setAjax == FALSE)
        {
            if(AUDITAR) {
                echo "Ocurrio una <b>Exception</b> en <b>{$archivo} - {$linea}</b>:<br><br>Codigo: {$numero}<br>{$mensaje}";
            }
            else {
                echo "Ocurrio una <b>Exception</b>:<br><br>$mensaje";
            }
        }
        else
        {

            $output = ['message' => $mensaje];
            if(AUDITAR) {
                $output['code'] = $numero;
                $output['file'] = $archivo;
                $output['line'] = $linea;
                $output['traces'] = $trazas;
            }
            echo Response::json($output, $status = 'exception');
        }

        exit;
    }

    public static function error(int $numero, string $mensaje, string $archivo, int $linea, array $context = []) {
        if(self::$setAjax == FALSE)
        {
            if(AUDITAR) {
                echo "Ocurrio un <b>Error</b> en <b>{$archivo} - {$linea}</b>:<br><br>Codigo: {$numero}<br>{$mensaje}";
            }
            else {
                echo "Ocurrio un <b>Error</b>:<br><br>{$mensaje}";
            }
        }
        else
        {
            $output = ['message' => $mensaje];
            if(AUDITAR) {
                $output['code'] = $numero;
                $output['file'] = $archivo;
                $output['line'] = $linea;
            }
            echo Response::json($output, $status = 'error');
        }

        exit;
    }

    public static function nulo() {
        return;
    }
}

set_exception_handler("Handler::exception");
set_error_handler("Handler::error");