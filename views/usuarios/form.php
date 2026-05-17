<?php
$selectedPermissions = json_decode((string) ($user['permissoes'] ?? '[]'), true) ?: [];
?>
<form method="post" action="<?= $user ? url('/usuarios/' . $user['id'] . '/atualizar') : url('/usuarios') ?>" class="soft-card rounded-3xl p-6">
  <?= csrf_field() ?>
  <div class="grid gap-5 lg:grid-cols-2">
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Nome</span>
      <input class="form-input" name="nome" required value="<?= e(old('nome', $user['nome'] ?? '')) ?>">
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">E-mail</span>
      <input class="form-input" type="email" name="email" required value="<?= e(old('email', $user['email'] ?? '')) ?>">
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Senha <?= $user ? '(preencha para alterar)' : '' ?></span>
      <input class="form-input" type="password" name="senha" <?= $user ? '' : 'required' ?>>
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Perfil</span>
      <select class="form-select" name="perfil">
        <?php foreach ($profiles as $key => $label): ?>
          <option value="<?= e($key) ?>" <?= old('perfil', $user['perfil'] ?? 'usuario') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Status</span>
      <select class="form-select" name="status">
        <option value="ativo" <?= old('status', $user['status'] ?? 'ativo') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
        <option value="inativo" <?= old('status', $user['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
      </select>
    </label>
  </div>

  <div class="mt-6">
    <h2 class="text-base font-black text-slate-950">Permissões do colaborador</h2>
    <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
      <?php foreach ($permissions as $key => $label): ?>
        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700">
          <input class="h-4 w-4 rounded border-slate-300 text-blue-600" type="checkbox" name="permissoes[]" value="<?= e($key) ?>" <?= in_array($key, $selectedPermissions, true) ? 'checked' : '' ?>>
          <?= e($label) ?>
        </label>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="mt-8 flex flex-wrap gap-3">
    <button class="btn-primary" type="submit"><i data-lucide="save" class="h-4 w-4"></i>Salvar</button>
    <a class="btn-secondary" href="<?= url('/usuarios') ?>">Cancelar</a>
  </div>
</form>
