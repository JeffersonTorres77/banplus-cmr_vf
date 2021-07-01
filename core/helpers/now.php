<?php

function now($format = 'Y-m-d H:i:s') {
    return date($format, strtotime('-6 hours'));
}