<?php

// Ruta base del proyecto
define('BASE_DIR', trim(str_replace('\\', '/', realpath(__DIR__.'/../')), '/'));

// IP del cliente
if( !isset($_SERVER['REMOTE_ADDR']) ) define('IP_CLIENTE', NULL);
else define('IP_CLIENTE', $_SERVER['REMOTE_ADDR']);

// Constantes del archivo config.ini
function config($key, $default = NULL) {
    if( !defined('CONFIG') ) {
        $config_file = BASE_DIR."/config.ini";
        define('CONFIG', parse_ini_file($config_file));
    }
    
    if( isset(CONFIG[$key]) ) return CONFIG[$key];
    else return $default;
}

define('BASE_URL', trim(config('SISTEMA.URL_BASE'), '/'));
define('AUDITAR', (config('SISTEMA.AUDITAR') == '1') ? TRUE : FALSE);