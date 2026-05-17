<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
  <div>
    <p class="text-sm font-bold text-slate-500">Ticket <?= e($improvement['ticket'] ?? ('#' . (int) $improvement['id'])) ?></p>
    <h2 class="text-2xl font-black text-slate-950"><?= e($improvement['titulo']) ?></h2>
  </div>
  <a class="btn-secondary" href="<?= url('/melhorias/' . $improvement['id']) ?>"><i data-lucide="arrow-left" class="h-4 w-4"></i>Voltar</a>
</div>
<form method="post" action="<?= url('/swot/' . $improvement['id']) ?>" class="grid gap-5 lg:grid-cols-2">
  <?= csrf_field() ?>
  <?php foreach (['forcas' => 'Forças', 'fraquezas' => 'Fraquezas', 'oportunidades' => 'Oportunidades', 'ameacas' => 'Ameaças'] as $field => $label): ?>
    <label class="soft-card rounded-3xl p-6">
      <span class="mb-3 block text-lg font-black text-slate-950"><?= e($label) ?></span>
      <textarea class="form-textarea" name="<?= e($field) ?>"><?= e($swot[$field] ?? '') ?></textarea>
    </label>
  <?php endforeach; ?>
  <div class="lg:col-span-2 flex flex-wrap gap-3">
    <button class="btn-primary" type="submit"><i data-lucide="save" class="h-4 w-4"></i>Salvar SWOT</button>
    <a href="<?= url('/swot') ?>" class="btn-secondary">Cancelar</a>
  </div>
</form>
