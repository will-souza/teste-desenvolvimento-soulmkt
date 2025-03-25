<?php
namespace App\Controllers;

use App\Core\Uploader;
use App\Models\Csv;

class UploadController {
    public function handleUpload(array $file, string $delimiter) {
        try {
            $uploader = new Uploader($file);
            $uploader->validate();

            $csv = new Csv($uploader->getTempPath(), $delimiter);
            $data = $csv->process();

            return [
                'status' => 'success',
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
