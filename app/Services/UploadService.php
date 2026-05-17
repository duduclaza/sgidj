<?php

namespace App\Services;

use RuntimeException;

class UploadService
{
    private array $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt'];
    private int $maxSize = 10485760;

    public function store(array $file): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Falha no upload do arquivo.');
        }

        if (($file['size'] ?? 0) > $this->maxSize) {
            throw new RuntimeException('O arquivo excede o limite de 10MB.');
        }

        $original = (string) ($file['name'] ?? 'arquivo');
        $extension = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions, true)) {
            throw new RuntimeException('Tipo de arquivo não permitido.');
        }

        $targetDir = base_path('storage/uploads/' . date('Y/m'));
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $target = $targetDir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new RuntimeException('Não foi possível salvar o arquivo.');
        }

        return [
            'nome_original' => $original,
            'caminho' => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $target),
            'mime_type' => mime_content_type($target) ?: 'application/octet-stream',
            'tamanho' => (int) $file['size'],
        ];
    }
}
