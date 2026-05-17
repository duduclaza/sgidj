<div class="mb-5">
  <form method="get" class="flex max-w-xl flex-col gap-3 sm:flex-row">
    <input class="form-input" name="q" value="<?= e($filters['q']) ?>" placeholder="Buscar ação, tabela ou detalhe">
    <button class="btn-secondary" type="submit"><i data-lucide="search" class="h-4 w-4"></i>Filtrar</button>
  </form>
</div>
<section class="soft-card overflow-hidden rounded-3xl">
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>Data</th><th>Usuário</th><th>Ação</th><th>Tabela</th><th>Registro</th><th>IP</th><th>Detalhes</th></tr></thead>
      <tbody>
      <?php foreach ($logs as $log): ?>
        <tr>
          <td><?= datetime_br($log['created_at']) ?></td>
          <td><?= e($log['usuario_nome'] ?? 'Sistema') ?></td>
          <td><span class="badge bg-blue-100 text-blue-700"><?= e($log['acao']) ?></span></td>
          <td><?= e($log['tabela']) ?></td>
          <td><?= e($log['registro_id'] ?? '-') ?></td>
          <td><?= e($log['ip'] ?? '-') ?></td>
          <td class="max-w-md"><?= e($log['detalhes'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$logs): ?><tr><td colspan="7" class="text-center text-slate-500">Nenhum log encontrado.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
