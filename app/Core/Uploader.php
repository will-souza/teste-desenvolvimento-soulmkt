<?php

namespace App\Core;

class Uploader {
    private $file;
    private $allowedTypes = ['text/csv', 'text/plain', 'application/csv'];
    private $maxSize = 10485760;

    public function __construct(array $file) {
        $this->file = $file;
    }

    public function validate(): bool {
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Erro no envio do arquivo');
        }

        if ($this->file['size'] > $this->maxSize) {
            throw new \LengthException('Arquivo ultrapassa o limite de tamanho permitido (10MB)');
        }

        if (!in_array($this->getMimeType(), $this->allowedTypes)) {
            throw new \InvalidArgumentException('Formato de arquivo inválido, apenas CSV.');
        }

        return true;
    }

    public function getMimeType(): string {
        return mime_content_type($this->file['tmp_name']);
    }

    public function getTempPath(): string {
        return $this->file['tmp_name'];
    }
}
