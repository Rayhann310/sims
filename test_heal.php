<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();
$schemaPath = __DIR__ . '/app/config/database_schema.php';
$schema = require $schemaPath;

try {
    foreach ($schema as $tableName => $definition) {
        $db->query("SHOW TABLES LIKE :table_name");
        $db->bind('table_name', $tableName);
        $db->execute();
        
        if ($db->rowCount() == 0) {
            echo "Creating $tableName...\n";
            $db->query($definition['create_sql']);
            $db->execute();
        } else {
            $db->query("SHOW COLUMNS FROM `$tableName`");
            $existingColsRaw = $db->resultSet();
            $existingCols = array_map(function($c) { return $c['Field']; }, $existingColsRaw);

            foreach ($definition['columns'] as $colName => $colType) {
                if (!in_array($colName, $existingCols)) {
                    echo "Adding column $colName to $tableName...\n";
                    $db->query("ALTER TABLE `$tableName` ADD COLUMN `$colName` $colType");
                    $db->execute();
                }
            }
        }
    }
    echo "Success!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
