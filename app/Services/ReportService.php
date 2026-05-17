<?php

namespace App\Services;

class ReportService
{
    public function csv(array $rows): string
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Ticket', 'Título', 'Departamento', 'Status', 'Prioridade', 'Responsável'], ';');
        foreach ($rows as $row) {
            fputcsv($handle, [
                $row['ticket'] ?? ('#' . $row['id']),
                $row['titulo'],
                $row['departamento_nome'],
                $row['status'],
                $row['prioridade'],
                $row['responsavel_nome'],
            ], ';');
        }
        rewind($handle);
        return stream_get_contents($handle);
    }

    public function pdf(array $rows, array $filters = []): string
    {
        $lines = ['Relatório de Melhorias Contínuas', 'Gerado em ' . date('d/m/Y H:i'), ''];
        foreach ($filters as $key => $value) {
            if ($value !== '') {
                $lines[] = ucfirst((string) $key) . ': ' . $value;
            }
        }
        $lines[] = '';

        foreach ($rows as $row) {
            $lines[] = ($row['ticket'] ?? "#{$row['id']}") . " {$row['titulo']}";
            $lines[] = "Status: {$row['status']} | Prioridade: {$row['prioridade']} | Departamento: " . ($row['departamento_nome'] ?? '-');
            $lines[] = "Responsável: " . ($row['responsavel_nome'] ?? '-');
            $lines[] = '';
        }

        return $this->minimalPdf($lines);
    }

    private function minimalPdf(array $lines): string
    {
        $content = "BT /F1 10 Tf 50 790 Td 14 TL\n";
        foreach ($lines as $line) {
            $safe = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], substr((string) $line, 0, 110));
            $content .= "({$safe}) Tj T*\n";
        }
        $content .= "ET";

        $objects = [];
        $objects[] = "<< /Type /Catalog /Pages 2 0 R >>";
        $objects[] = "<< /Type /Pages /Kids [3 0 R] /Count 1 >>";
        $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>";
        $objects[] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
        $objects[] = "<< /Length " . strlen($content) . " >>\nstream\n{$content}\nendstream";

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $i => $object) {
            $offsets[] = strlen($pdf);
            $number = $i + 1;
            $pdf .= "{$number} 0 obj\n{$object}\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= str_pad((string) $offset, 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }
        $pdf .= "trailer << /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";
        return $pdf;
    }
}
