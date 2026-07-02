<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();
$db->query('SELECT id, username, length(password) as len, password FROM users ORDER BY id DESC LIMIT 5');
$res = $db->resultSet();
print_r($res);
