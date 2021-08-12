<?php

function timestamp_to_text($time) {
    $aTime = [
        'dias' => 0,
        'horas' => 0,
        'minutos' => 0,
    ];
    
    $divDias = 60*60*24;
    $divHoras = 60*60;
    $divMinutos = 60;

    if($time / $divDias >= 1) {
        $aTime['dias'] = bcdiv( $time / $divDias, '1', 0 );
        $time = $time - ($aTime['dias'] * $divDias);
    }
    if($time / $divHoras >= 1) {
        $aTime['horas'] = bcdiv( $time / $divHoras, '1', 0 );
        $time = $time - ($aTime['horas'] * $divHoras);
    }
    if($time / $divMinutos >= 1) {
        $aTime['minutos'] = bcdiv( $time / $divMinutos, '1', 0 );
        $time = $time - ($aTime['minutos'] * $divMinutos);
    }
    
    $mensaje = "";
    if($aTime['dias'] > 0) {
        if($mensaje != "") $mensaje .= " ";
        $mensaje .= $aTime['dias']." dia";
        if($aTime['dias'] > 1) $mensaje .= "s";
    }
    if($aTime['horas'] > 0) {
        if($mensaje != "") $mensaje .= ", ";
        $mensaje .= $aTime['horas']." hora";
        if($aTime['horas'] > 1) $mensaje .= "s";
    }
    if($aTime['minutos'] > 0) {
        if($mensaje != "") $mensaje .= ", ";
        $mensaje .= $aTime['minutos']." minuto";
        if($aTime['minutos'] > 1) $mensaje .= "s";
    }
    
    $mensaje = "";
    if($aTime['dias'] > 0) {
        if($mensaje != "") $mensaje .= " ";
        $mensaje .= $aTime['dias']." dia";
        if($aTime['dias'] > 1) $mensaje .= "s";
    }
    if($aTime['horas'] > 0) {
        if($mensaje != "") $mensaje .= ", ";
        $mensaje .= $aTime['horas']." hora";
        if($aTime['horas'] > 1) $mensaje .= "s";
    }
    if($aTime['minutos'] > 0) {
        if($mensaje != "") $mensaje .= ", ";
        $mensaje .= $aTime['minutos']." minuto";
        if($aTime['minutos'] > 1) $mensaje .= "s";
    }

    if($mensaje == "") $mensaje = "Menos de un minuto";
    
    return $mensaje;
}