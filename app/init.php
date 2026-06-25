<?php
if (file_exists('../vendor/autoload.php')) {
    require_once '../vendor/autoload.php';
}

// Load Environment Variables
$dotenvPath = dirname(__DIR__);
if (class_exists('Dotenv\Dotenv') && file_exists($dotenvPath . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable($dotenvPath);
    $dotenv->load();
}

require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';
require_once 'core/Flasher.php';
require_once 'config/config.php';
