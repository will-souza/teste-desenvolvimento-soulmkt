<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\UploadController;

$controller = new UploadController();
$result = $controller->handleUpload($_FILES['csv_file'], $_POST['delimiter']);

echo $result;