<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <form method="get" class="grid gap-3 sm:grid-cols-[1fr_160px_auto]">
    <input class="form-input" name="q" value="<?= e($filters['q']) ?>" placeholder="Buscar departamento">
    <select class="form-select" name="status">
      <option value="">Todos</option>
      <option value="ativo" <?= $filters['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
      <option value="inativo" <?= $filters['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
    </select>
    <button class="btn-secondary" type="submit"><i data-lucide="search" class="h-4 w-4"></i>Filtrar</button>
  </form>
  <a href="<?= url('/departamentos/novo') ?>" class="btn-primary"><i data-lucide="plus" class="h-4 w-4"></i>Novo departamento</a>
</div>

<section class="soft-card overflow-hidden rounded-3xl">
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>Departamento</th><th>Responsável</th><th>Status</th><th class="text-right">Ações</th></tr></thead>
      <tbody>
      <?php foreach ($departments as $department): ?>
        <tr>
          <td><strong class="text-slate-900"><?= e($department['nome']) ?></strong></td>
          <td><?= e($department['responsavel_nome'] ?? '-') ?></td>
          <td><span class="badge <?= $department['status'] === 'ativo' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' ?>"><?= e($department['status']) ?></span></td>
          <td>
            <div class="flex justify-end gap-2">
              <a class="btn-secondary !p-2.5" href="<?= url('/departamentos/' . $department['id'] . '/editar') ?>" title="Editar"><i data-lucide="pencil" class="h-4 w-4"></i></a>
              <form method="post" action="<?= url('/departamentos/' . $department['id'] . '/excluir') ?>" data-confirm="Excluir este departamento?">
                <?= csrf_field() ?>
                <button class="btn-danger !p-2.5" title="Excluir"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$departments): ?><tr><td colspan="4" class="text-center text-slate-500">Nenhum departamento encontrado.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
