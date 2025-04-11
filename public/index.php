<?php

use App\Controllers\RealtorController;

require_once __DIR__ . '/../config/config.php';

$realtorController = new RealtorController();
$realtorController->postRequestProcess();
$realtorController->index();

?>

