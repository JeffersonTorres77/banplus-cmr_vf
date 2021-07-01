<?php

// Archivos basicos
require_once(__DIR__."/constantes.php");
require_once(__DIR__."/handlers.php");
require_once(__DIR__."/system.php");

// Autoload
require_once(BASE_DIR."/vendor/autoload.php");

// Helpers
$helpers = System::getFolderFiles( BASE_DIR."/core/helpers" );
foreach($helpers as $file) { require($file); }

// Models
$models = System::getFolderFiles( BASE_DIR."/core/models" );
foreach($models as $file) { require($file); }