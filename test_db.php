<?php
$lines = file('app/models/EraporModel.php');
foreach($lines as $i => $l) {
    if(strpos($l, 'function getRombelWaliKelas') !== false) {
        echo "Line " . ($i+1) . ": " . trim($l) . "\n";
    }
}
