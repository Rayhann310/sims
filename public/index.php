<?php

// Atur lingkungan aplikasi: 'development' atau 'production'
define('ENVIRONMENT', 'development');

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

if( !session_id() ) session_start();

require_once '../app/init.php';

$app = new App();
