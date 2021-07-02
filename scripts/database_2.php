<?php

require_once(__DIR__."/../core/panel.php");

// Iniciamos la base de datos
Database::iniciar();

$files = System::getFolderFiles( BASE_DIR."/scripts/database_2" );
foreach($files as $file) { require($file); }

$table_contactos->down()->up()->default();
$table_financieros_juridico->down()->up()->default();
$table_financieros_natural->down()->up()->default();
$table_segmentos->down()->up()->default();
$table_president->down()->up()->default();