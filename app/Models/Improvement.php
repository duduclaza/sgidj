<?php

namespace App\Models;

use App\Core\Model;
use Throwable;

class Improvement extends Model
{
    protected string $table = 'melhorias';
    protected array $fillable = [
        'titulo',
        'departamento_id',
        'area_setor',
        'outra_area',
        'descricao_problema',
        'melhoria_sugerida',
        'resultado_esperado',
        'prioridade',
        'status',
        'responsavel_id',
        'responsavel_nome',
        'responsavel_preenchimento',
        'o_que',
        'quem',
        'onde',
        'por_que',
        'quando',
        'como',
        'quanto',
        'prazo',
        'ganho_estimado',
        'data_abertura',
        'data_conclusao',
        'causa_raiz',
        'observacoes',
        'criado_por',
    ];

    public function createWithTicket(array $data): array
    {
        $db = $this->db();
        $startedTransaction = !$db->inTransaction();

        if ($startedTransaction) {
            $db->beginTransaction();
        }

        try {
            $id = $this->create($data);
            $ticket = $this->assignTicket($id, $data['data_abertura'] ?? null);

            if ($startedTransaction) {
                $db->commit();
            }

            return ['id' => $id, 'ticket' => $ticket];
        } catch (Throwable $exception) {
            if ($startedTransaction && $db->inTransaction()) {
                $db->rollBack();
            }

            throw $exception;
        }
    }

    public function assignTicket(int $id, ?string $date = null): string
    {
        $current = $this->find($id);
        if (!empty($current['ticket'])) {
            return (string) $current['ticket'];
        }

        $ticket = $this->formatTicket($id, $date ?: ($current['data_abertura'] ?? null));
        $statement = $this->db()->prepare('UPDATE melhorias SET ticket = :ticket WHERE id = :id');
        $statement->execute(['ticket' => $ticket, 'id' => $id]);

        return $ticket;
    }

    public function findByTicket(string $ticket): ?array
    {
        $normalized = $this->normalizeTicket($ticket);
        if ($normalized === '') {
            return null;
        }

        $statement = $this->db()->prepare(
            "SELECT m.ticket, m.titulo, m.status, m.prioridade, m.data_abertura, m.updated_at,
                    d.nome AS departamento_nome
             FROM melhorias m
             LEFT JOIN departamentos d ON d.id = m.departamento_id
             WHERE m.ticket = :ticket
             LIMIT 1"
        );
        $statement->execute(['ticket' => $normalized]);
        $item = $statement->fetch();

        return $item ?: null;
    }

    public function normalizeTicket(string $ticket): string
    {
        return strtoupper(trim(preg_replace('/\s+/', '', $ticket) ?? ''));
    }

    public function list(array $filters = []): array
    {
        [$where, $params] = $this->buildFilters($filters);
        $sql = "SELECT m.*, d.nome AS departamento_nome, COALESCE(NULLIF(m.responsavel_nome, ''), u.nome) AS responsavel_nome, c.nome AS criador_nome
                FROM melhorias m
                LEFT JOIN departamentos d ON d.id = m.departamento_id
                LEFT JOIN usuarios u ON u.id = m.responsavel_id
                LEFT JOIN usuarios c ON c.id = m.criado_por
                {$where}
                ORDER BY m.created_at DESC";

        $statement = $this->db()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function findWithRelations(int $id): ?array
    {
        $statement = $this->db()->prepare(
            "SELECT m.*, d.nome AS departamento_nome, COALESCE(NULLIF(m.responsavel_nome, ''), u.nome) AS responsavel_nome, c.nome AS criador_nome
             FROM melhorias m
             LEFT JOIN departamentos d ON d.id = m.departamento_id
             LEFT JOIN usuarios u ON u.id = m.responsavel_id
             LEFT JOIN usuarios c ON c.id = m.criado_por
             WHERE m.id = :id LIMIT 1"
        );
        $statement->execute(['id' => $id]);
        $item = $statement->fetch();
        return $item ?: null;
    }

    public function dashboardStats(): array
    {
        $db = $this->db();
        $total = (int) $db->query('SELECT COUNT(*) FROM melhorias')->fetchColumn();
        $open = (int) $db->query("SELECT COUNT(*) FROM melhorias WHERE status = 'Aberta'")->fetchColumn();
        $done = (int) $db->query("SELECT COUNT(*) FROM melhorias WHERE status = 'Concluída'")->fetchColumn();
        $implantation = (int) $db->query("SELECT COUNT(*) FROM melhorias WHERE status = 'Em implantação'")->fetchColumn();

        $byStatus = $db->query('SELECT status, COUNT(*) total FROM melhorias GROUP BY status ORDER BY total DESC')->fetchAll();
        $byDepartment = $db->query('SELECT d.nome, COUNT(m.id) total FROM departamentos d LEFT JOIN melhorias m ON m.departamento_id = d.id GROUP BY d.id, d.nome ORDER BY total DESC LIMIT 8')->fetchAll();
        $monthly = $db->query("SELECT DATE_FORMAT(data_abertura, '%Y-%m') mes, COUNT(*) total FROM melhorias GROUP BY mes ORDER BY mes ASC LIMIT 12")->fetchAll();

        return compact('total', 'open', 'done', 'implantation', 'byStatus', 'byDepartment', 'monthly');
    }

    public function report(array $filters = []): array
    {
        return $this->list($filters);
    }

    private function buildFilters(array $filters): array
    {
        $sql = 'WHERE 1=1';
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= ' AND (m.ticket LIKE :q OR m.titulo LIKE :q OR m.descricao_problema LIKE :q OR m.melhoria_sugerida LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        foreach (['status', 'prioridade'] as $field) {
            if (!empty($filters[$field])) {
                $sql .= " AND m.{$field} = :{$field}";
                $params[$field] = $filters[$field];
            }
        }

        if (!empty($filters['departamento_id'])) {
            $sql .= ' AND m.departamento_id = :departamento_id';
            $params['departamento_id'] = $filters['departamento_id'];
        }

        if (!empty($filters['responsavel_id'])) {
            $sql .= ' AND m.responsavel_id = :responsavel_id';
            $params['responsavel_id'] = $filters['responsavel_id'];
        }

        if (!empty($filters['responsavel_nome'])) {
            $sql .= ' AND (m.responsavel_nome LIKE :responsavel_nome OR u.nome LIKE :responsavel_nome)';
            $params['responsavel_nome'] = '%' . $filters['responsavel_nome'] . '%';
        }

        if (!empty($filters['inicio'])) {
            $sql .= ' AND m.data_abertura >= :inicio';
            $params['inicio'] = $filters['inicio'];
        }

        if (!empty($filters['fim'])) {
            $sql .= ' AND m.data_abertura <= :fim';
            $params['fim'] = $filters['fim'];
        }

        return [$sql, $params];
    }

    private function formatTicket(int $id, ?string $date = null): string
    {
        $timestamp = $date ? strtotime($date) : false;
        $year = $timestamp ? date('Y', $timestamp) : date('Y');

        return 'MEL-' . $year . '-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }
}
