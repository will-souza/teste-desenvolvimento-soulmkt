<?php
namespace App\Controllers;

use App\Core\Uploader;
use App\Models\Csv;

class UploadController {
    public function handleUpload(array $file, string $delimiter) {
        try {
            $uploader = $this->createUploader($file);
            $uploader->validate();

            $csv = $this->createCsv($uploader->getTempPath(), $delimiter);
            $data = $csv->process();

            return [
                'status' => 'success',
                'data' => $data,
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    
    protected function createUploader(array $file): Uploader
    {
        return new Uploader($file);
    }
    
    protected function createCsv(string $path, string $delimiter): Csv
    {
        return new Csv($path, $delimiter);
    }
}
