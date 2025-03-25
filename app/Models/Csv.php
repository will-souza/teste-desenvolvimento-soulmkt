<?php
namespace App\Models;
class Csv {
    private $filePath;
    private $delimiter;

    public function __construct(string $filePath, string $delimiter) {
        $this->filePath = $filePath;
        $this->delimiter = $delimiter;
    }

    public function process () {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException('Arquivo não encontrado.');
        }

        $data = [];
        $header = [];
        $lineNumber = 0;

        if (($handle = fopen($this->filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $this->delimiter)) !== false) {
                $lineNumber++;
                
                if (empty(array_filter($row))) continue;

                if ($lineNumber === 1) {
                    $header = $row;
                    continue;
                }

                $data[] = $this->mapRow($header, $row);
            }
            fclose($handle);
        }

        usort($data, function($a, $b) {
            return strcmp($a['nome'], $b['nome']);
        });

        return $data;
    }

    private function mapRow(array $header, array $row): array {
        $mapped = [];
        foreach ($header as $index => $key) {
            $mapped[$key] = $row[$index] ?? null;
        }
        return $mapped;
    }
}
