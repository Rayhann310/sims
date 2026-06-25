<?php
if (!session_id()) session_start();
if (file_exists('vendor/autoload.php')) require_once 'vendor/autoload.php';
require_once 'app/init.php';
$app = new App();
