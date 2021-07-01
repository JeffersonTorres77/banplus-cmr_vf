<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function index() {
        Sesion::cerrar();

        if(Request::esAjax()) return Response::json(['ok' => TRUE]);
        else {
            header('location: '.BASE_URL.'/Login/');
            exit;
        }
    }
}