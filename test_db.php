<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();
$db->query('SHOW CREATE TABLE spmb_peserta');
$res = $db->single();
echo $res['Create Table'];
