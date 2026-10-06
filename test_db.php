<?php
require 'app/config/config.php';
require 'app/core/Database.php';
require 'app/core/Controller.php';

class FakeController extends Controller {
    public function getModel() {
        require_once 'app/models/EraporModel.php';
        return new EraporModel();
    }
}

$ctrl = new FakeController();
$model = $ctrl->getModel();

// Simulation of /erapor/cetak initialization (before $_GET is parsed)
try {
    $rombel_list = $model->getAllRombel();
    echo "Rombel OK\n";
} catch(Exception $e) {
    echo "Rombel Error: " . $e->getMessage() . "\n";
}
