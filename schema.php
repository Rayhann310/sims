<?php
require_once 'app/init.php';
$db = new Database();
$db->query("SHOW TABLES");
$tables = $db->resultSet();
foreach($tables as $t) {
    $tableName = array_values($t)[0];
    echo "TABLE: " . $tableName . "\n";
    $db->query("DESCRIBE " . $tableName);
    $cols = $db->resultSet();
    foreach($cols as $c) {
        echo "  - " . $c['Field'] . " (" . $c['Type'] . ")\n";
    }
}
