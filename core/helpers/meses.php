<?php

function nombre_mes($numero_mes) {
    $numero_mes = (int) $numero_mes;
    if($numero_mes < 1 || $numero_mes > 12) return NULL;

    $meses = [
        [ 'nombre' => 'Enero',      'nombre_corto' => 'ENE' ],
        [ 'nombre' => 'Febrero',    'nombre_corto' => 'FEB' ],
        [ 'nombre' => 'Marzo',      'nombre_corto' => 'MAR' ],
        [ 'nombre' => 'Abril',      'nombre_corto' => 'ABR' ],
        [ 'nombre' => 'Mayo',       'nombre_corto' => 'MAY' ],
        [ 'nombre' => 'Junio',      'nombre_corto' => 'JUN' ],
        [ 'nombre' => 'Julio',      'nombre_corto' => 'JUL' ],
        [ 'nombre' => 'Agosto',     'nombre_corto' => 'AGO' ],
        [ 'nombre' => 'Septiembre', 'nombre_corto' => 'SEP' ],
        [ 'nombre' => 'Octubre',    'nombre_corto' => 'OCT' ],
        [ 'nombre' => 'Noviembre',  'nombre_corto' => 'NOV' ],
        [ 'nombre' => 'Diciembre',  'nombre_corto' => 'DIC' ],
    ];

    return $meses[ $numero_mes - 1 ];
}