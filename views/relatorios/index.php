<?php $query = array_filter($filters, fn ($value) => $value !== ''); ?>
<section class="soft-card rounded-3xl p-6">
  <form method="get" class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Status</span>
      <select class="form-select" name="status">
        <option value="">Todos</option>
        <?php foreach ($statuses as $status): ?><option value="<?= e($status) ?>" <?= $filters['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?>
      </select>
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Departamento</span>
      <select class="form-select" name="departamento_id">
        <option value="">Todos</option>
        <?php foreach ($departments as $department): ?><option value="<?= (int) $department['id'] ?>" <?= (string) $filters['departamento_id'] === (string) $department['id'] ? 'selected' : '' ?>><?= e($department['nome']) ?></option><?php endforeach; ?>
      </select>
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Responsável</span>
      <input class="form-input" name="responsavel_nome" value="<?= e($filters['responsavel_nome']) ?>" placeholder="Nome do responsável">
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Início</span>
      <input class="form-input" type="date" name="inicio" value="<?= e($filters['inicio']) ?>">
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Fim</span>
      <input class="form-input" type="date" name="fim" value="<?= e($filters['fim']) ?>">
    </label>
    <div class="flex items-end gap-2">
      <button class="btn-secondary w-full" type="submit"><i data-lucide="filter" class="h-4 w-4"></i>Filtrar</button>
    </div>
  </form>
  <div class="mt-5 flex flex-wrap gap-3">
    <a class="btn-primary" href="<?= url('/relatorios/exportar?' . http_build_query($query + ['format' => 'csv'])) ?>"><i data-lucide="file-spreadsheet" class="h-4 w-4"></i>CSV/Excel</a>
    <a class="btn-secondary" href="<?= url('/relatorios/exportar?' . http_build_query($query + ['format' => 'pdf'])) ?>"><i data-lucide="file-text" class="h-4 w-4"></i>PDF</a>
  </div>
</section>

<section class="soft-card mt-6 overflow-hidden rounded-3xl">
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>Ticket</th><th>Título</th><th>Status</th><th>Departamento</th><th>Responsável</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <td class="font-black text-slate-900"><?= e($row['ticket'] ?? '-') ?></td>
          <td><a class="font-black text-slate-950 hover:text-blue-700" href="<?= url('/melhorias/' . $row['id']) ?>"><?= e($row['titulo']) ?></a></td>
          <td><?= e($row['status']) ?></td>
          <td><?= e($row['departamento_nome'] ?? '-') ?></td>
          <td><?= e($row['responsavel_nome'] ?? '-') ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="5" class="text-center text-slate-500">Nenhum dado para os filtros selecionados.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
