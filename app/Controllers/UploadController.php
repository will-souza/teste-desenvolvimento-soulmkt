<?php
namespace App\Controllers;

use App\Core\Uploader;
use App\Models\Csv;

class UploadController {
    public function handleUpload(array $file, string $delimiter) {
        $uploader = new Uploader($file);

        $csv = new Csv($uploader->getTempPath(), $delimiter);
        $data = $csv->process();

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
