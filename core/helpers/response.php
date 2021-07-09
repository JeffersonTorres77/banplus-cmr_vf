<?php

class Response
{
    public static function error_404($message = "") {
        if(!Request::esAjax())
        {
            $loader = new \Twig\Loader\FilesystemLoader(BASE_DIR);
            $twig = new \Twig\Environment($loader);
            $twig = self::basic_functions($twig);
            echo $twig->render("app/views/error_404.html", array_merge(self::basic_parameters(), [
                'message' => $message
            ]));
            exit;
        }
        else
        {
            if(AUDITAR) throw new Exception( "Error 404 - {$message}" );
            else        throw new Exception( "Error 404" );
        }
    }

    public static function sin_permisos($message = "") {
        if(!Request::esAjax())
        {
            $loader = new \Twig\Loader\FilesystemLoader(BASE_DIR);
            $twig = new \Twig\Environment($loader);
            $twig = self::basic_functions($twig);
            echo $twig->render("app/views/sin_permisos.html", array_merge(self::basic_parameters(), [
                'message' => $message
            ]));
            exit;
        }
        else
        {
            if(AUDITAR) throw new Exception( "No tiene permisos - {$message}" );
            else        throw new Exception( "No tiene permisos" );
        }
    }
    
    public static function view($filename, $parameters = []) {
        $module = Request::getControlador();
        $pathfile = "app/modules/{$module}/{$filename}";
        if(!file_exists($pathfile)) self::error_404("Archivo <b>{$filename}</b> no existe en el modulo <b>{$module}</b>.");

        $loader = new \Twig\Loader\FilesystemLoader(BASE_DIR);
        $twig = new \Twig\Environment($loader);
        $twig = self::basic_functions($twig);
        return $twig->render($pathfile, array_merge(self::basic_parameters(), $parameters));
    }
    
    public static function json($data, $status = 'ok') {
        header('Content-type: application/json');
        return json_encode([
            'status' => $status,
            'body' => $data
        ]);
    }

    private static function basic_parameters() {
        if( !isset($_COOKIE['sidebar_collapse']) ) $_COOKIE['sidebar_collapse'] = FALSE;

        $usuario = Sesion::usuario();
        $permisos = NULL;
        if($usuario != NULL) {
            $rol = Sesion::usuario()->rol;
            $permisos_aux = Permiso::select('id', 'slug')->get();
            $permisos = [];
            foreach($permisos_aux as $key => $permiso) {
                $permisos[$permiso->slug] = $rol->esValido($permiso->slug);
            }
        }
        
        return [
            'SISTEMA' => [
                'NOMBRE' => config('SISTEMA.NOMBRE'),
                'VERSION' => config('SISTEMA.VERSION')
            ],
            'AUDITAR' => AUDITAR,
            'BASE_URL' => BASE_URL,
            'USUARIO' => $usuario,
            'PERMISOS' => $permisos,
            'CONTROLADOR' => Request::getControlador(),
            'METODO' => Request::getMetodo(),
            'SIDEBAR_COLLAPSE' => boolval( $_COOKIE['sidebar_collapse'] ),
            'NOW' => now('Y-m-d'),
        ];
    }

    private static function basic_functions($twig) {
        $twig->addFunction(new \Twig\TwigFunction('template', function($name) {
            return "app/templates/{$name}.html";
        }));
        $twig->addFunction(new \Twig\TwigFunction('public_file', function($name) {
            $module = Request::getControlador();
            return BASE_URL."/app/modules/{$module}/public/{$name}";
        }));
        return $twig;
    }
}