<?php

require_once(__DIR__."/core/panel.php");

// Iniciamos los procesos principales
Database::iniciar();
Sesion::iniciar();
Request::analizar();

// Guardamos los datos de la peticion
$controlador = Request::getControlador();
$metodo = Request::getMetodo();
$parametros = Request::getParametros();

// Verificamos que el archivo solicitado exista
$pathModule = BASE_DIR."/app/modules/{$controlador}";
$pathFile = "{$pathModule}/controlador.php";


if(!file_exists($pathModule) || !is_dir($pathModule)) {
    Response::error_404("El modulo no existe.");
}
if(!file_exists($pathFile) || !is_file($pathFile)) {
    Response::error_404("El modulo no tiene controlador.");
}

require_once($pathFile);

// Verificamos que exista la clase 'controlador'
if( !class_exists('controlador') ) {
    Response::error_404("La clase 'controlador' no existe.");
}

// Verificamos que exista el metodo
if( !method_exists('controlador', $metodo) ) {
    Response::error_404("El metodo solicitado no existe.");
}

// Ejecutamos y mostramos la respuesta a la peticion
$controlador = new controlador;
$resp = $controlador->$metodo( ...$parametros );
echo $resp;

?>