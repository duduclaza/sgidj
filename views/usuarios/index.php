<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <form method="get" class="grid gap-3 sm:grid-cols-[1fr_180px_auto]">
    <input class="form-input" name="q" value="<?= e($filters['q']) ?>" placeholder="Buscar por nome ou e-mail">
    <select class="form-select" name="perfil">
      <option value="">Todos os perfis</option>
      <?php foreach ($profiles as $key => $label): ?>
        <option value="<?= e($key) ?>" <?= $filters['perfil'] === $key ? 'selected' : '' ?>><?= e($label) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn-secondary" type="submit"><i data-lucide="search" class="h-4 w-4"></i>Filtrar</button>
  </form>
  <a href="<?= url('/usuarios/novo') ?>" class="btn-primary"><i data-lucide="user-plus" class="h-4 w-4"></i>Novo usuário</a>
</div>

<section class="soft-card overflow-hidden rounded-3xl">
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Perfil</th>
          <th>Status</th>
          <th class="text-right">Ações</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><strong class="text-slate-900"><?= e($user['nome']) ?></strong></td>
          <td><?= e($user['email']) ?></td>
          <td><?= e($profiles[$user['perfil']] ?? $user['perfil']) ?></td>
          <td><span class="badge <?= $user['status'] === 'ativo' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' ?>"><?= e($user['status']) ?></span></td>
          <td>
            <div class="flex justify-end gap-2">
              <a class="btn-secondary !p-2.5" href="<?= url('/usuarios/' . $user['id'] . '/editar') ?>" title="Editar"><i data-lucide="pencil" class="h-4 w-4"></i></a>
              <form method="post" action="<?= url('/usuarios/' . $user['id'] . '/toggle') ?>">
                <?= csrf_field() ?>
                <button class="btn-secondary !p-2.5" title="Ativar/inativar"><i data-lucide="power" class="h-4 w-4"></i></button>
              </form>
              <form method="post" action="<?= url('/usuarios/' . $user['id'] . '/excluir') ?>" data-confirm="Excluir este usuário?">
                <?= csrf_field() ?>
                <button class="btn-danger !p-2.5" title="Excluir"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$users): ?>
        <tr><td colspan="5" class="text-center text-slate-500">Nenhum usuário encontrado.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
