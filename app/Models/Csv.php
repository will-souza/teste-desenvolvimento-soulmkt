<?php
namespace App\Models;
class Csv {
    private $filePath;
    private $delimiter;
    private $allowedKeys = ['codigo', 'nome', 'preco'];

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

        try {
            if (($handle = fopen($this->filePath, 'r')) !== false) {
                while (($row = fgetcsv($handle, 0, $this->delimiter)) !== false) {
                    $lineNumber++;
                    
                    if (empty(array_filter($row))) continue;
    
                    if ($lineNumber === 1) {
                        $header = $row;
                        continue;
                    }
    
                    $dataRow = $this->mapRow($header, $row);
    
                    if (!empty($dataRow)) {
                        $data[] = $this->mapRow($header, $row);
                    }
                }
                fclose($handle);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        usort($data, function($a, $b) {
            return strcmp($a['nome'], $b['nome']);
        });

        if (empty($data)) {
            throw new \UnexpectedValueException('Nenhum registro encontrado, verifique se o delimitador está correto');
        }
        return $data;
    }

    private function mapRow(array $header, array $row): array {
        $mapped = [];
        foreach ($header as $index => $key) {
            if (in_array($key, $this->allowedKeys)) {
                $mapped[$key] = $row[$index] ?? null;
            }
        }

        if (!empty($mapped)) {
            $mapped['copyAllowed'] = $this->isCopyAllowed($mapped['codigo']);
            $mapped['isRedLine'] = $this->isNegativeNumber($mapped['preco']);
        }

        return $mapped;
    }

    private function isCopyAllowed($value): bool {
        return preg_match('/[02468]/', $value) === 1;
    }

    private function isNegativeNumber($value): bool {
        return preg_replace('/[^0-9-]|-(?=.*-)/', '', $value) < 0;
    }
}
