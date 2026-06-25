<?php
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SCRIPT_NAME'] = '/index.php';
require 'app/init.php';
$db = new Database();
$db->query("SHOW COLUMNS FROM siswa");
print_r($db->resultSet());
