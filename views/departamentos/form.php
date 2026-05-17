<form method="post" action="<?= $department ? url('/departamentos/' . $department['id'] . '/atualizar') : url('/departamentos') ?>" class="soft-card rounded-3xl p-6">
  <?= csrf_field() ?>
  <div class="grid gap-5 lg:grid-cols-3">
    <label class="lg:col-span-2">
      <span class="mb-2 block text-sm font-bold text-slate-700">Nome do departamento</span>
      <input class="form-input" name="nome" required value="<?= e(old('nome', $department['nome'] ?? '')) ?>">
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Status</span>
      <select class="form-select" name="status">
        <option value="ativo" <?= old('status', $department['status'] ?? 'ativo') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
        <option value="inativo" <?= old('status', $department['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
      </select>
    </label>
    <label class="lg:col-span-3">
      <span class="mb-2 block text-sm font-bold text-slate-700">Responsável</span>
      <select class="form-select" name="responsavel_id">
        <option value="">Sem responsável definido</option>
        <?php foreach ($users as $user): ?>
          <option value="<?= (int) $user['id'] ?>" <?= (int) old('responsavel_id', $department['responsavel_id'] ?? 0) === (int) $user['id'] ? 'selected' : '' ?>><?= e($user['nome']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
  </div>
  <div class="mt-8 flex flex-wrap gap-3">
    <button class="btn-primary" type="submit"><i data-lucide="save" class="h-4 w-4"></i>Salvar</button>
    <a class="btn-secondary" href="<?= url('/departamentos') ?>">Cancelar</a>
  </div>
</form>
