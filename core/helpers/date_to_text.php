<?php

function date_to_text($date, $format = 'd/m/Y') {
    return date_format( date_create($date), $format );
}