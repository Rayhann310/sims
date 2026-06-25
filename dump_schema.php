<?php
require_once 'app/init.php';
$db = new Database();

$db->query("SHOW TABLES");
$tables = $db->resultSet();

$schema = [];

foreach($tables as $t) {
    $tableName = array_values($t)[0];
    
    // Get Create SQL
    $db->query("SHOW CREATE TABLE " . $tableName);
    $createRow = $db->single();
    $createSql = $createRow['Create Table'];
    
    // Clean up CREATE TABLE to add IF NOT EXISTS
    $createSql = str_replace("CREATE TABLE", "CREATE TABLE IF NOT EXISTS", $createSql);
    
    // Get Columns
    $db->query("SHOW COLUMNS FROM " . $tableName);
    $cols = $db->resultSet();
    
    $columnsDef = [];
    foreach($cols as $c) {
        $def = $c['Type'];
        if ($c['Null'] == 'NO') $def .= ' NOT NULL';
        
        if ($c['Default'] !== null) {
            if ($c['Default'] === 'CURRENT_TIMESTAMP' || is_numeric($c['Default'])) {
                $def .= ' DEFAULT ' . $c['Default'];
            } else {
                $def .= " DEFAULT '" . $c['Default'] . "'";
            }
        } elseif ($c['Null'] == 'YES' && $c['Default'] === null) {
            $def .= ' DEFAULT NULL';
        }
        
        if ($c['Extra']) {
            $def .= ' ' . $c['Extra'];
        }
        $columnsDef[$c['Field']] = $def;
    }
    
    $schema[$tableName] = [
        'create_sql' => $createSql,
        'columns' => $columnsDef
    ];
}

$export = "<?php\n\nreturn " . var_export($schema, true) . ";\n";
file_put_contents('app/config/database_schema.php', $export);
echo "Schema exported successfully to app/config/database_schema.php\n";
