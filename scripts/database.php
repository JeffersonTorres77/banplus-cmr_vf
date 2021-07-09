<?php

use Illuminate\Database\Capsule\Manager as DB;

require_once(__DIR__."/../core/panel.php");

// Iniciamos la base de datos
Database::iniciar();

$files = System::getFolderFiles( BASE_DIR."/scripts/database" );
foreach($files as $file) { require($file); }

$table_gestiones->down();
$table_resolucion_comite->down();
$table_membresia_president->down();
$table_tipos_llamadas->down();
$table_estatus_gestion->down();
$table_tipos_gestion->down();
$table_permisos_roles->down();
$table_permisos->down();
$table_usuarios->down();
$table_roles->down();

$table_roles->up()->default();
$table_usuarios->up()->default();
$table_permisos->up()->default();
$table_permisos_roles->up()->default();
$table_tipos_llamadas->up()->default();
$table_tipos_gestion->up()->default();
$table_estatus_gestion->up()->default();
$table_resolucion_comite->up()->default();
$table_membresia_president->up()->default();
$table_gestiones->up()->default();