<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        // Validamos la sesion
        Sesion::auth();
        // Validamos los permisos del menu
        if( !Sesion::usuario()->rol->esValido('menu_gestion_contacto') ) {
            if(!Request::esAjax()) Response::sin_permisos();
            else throw new Exception('Usted no tiene permisos para acceder a esta pagina.');
        }
    }

    public function index() {
        return Response::view('views/index.html');
    }
}