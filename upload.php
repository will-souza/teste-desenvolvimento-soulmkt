<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\UploadController;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new \RuntimeException('Método não permitido.');
    }

    if (!isset($_FILES['csv_file'], $_POST['delimiter'])) {
        throw new \InvalidArgumentException('Parâmetros faltando.');
    }

    $controller = new UploadController();
    $result = $controller->handleUpload($_FILES['csv_file'], $_POST['delimiter']);

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
