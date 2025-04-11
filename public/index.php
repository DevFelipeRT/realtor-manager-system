<?php

use App\Controllers\RealtorController;

require_once __DIR__ . '/../config/config.php';

var_dump(PUBLIC_URL);

$realtorController = new RealtorController();
$realtorController->postRequestProcess();
$realtorController->index();

?>

