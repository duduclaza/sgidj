<?php
$fields = [
    'what_text' => 'What / O quê',
    'why_text' => 'Why / Por quê',
    'where_text' => 'Where / Onde',
    'when_text' => 'When / Quando',
    'who_text' => 'Who / Quem',
    'how_text' => 'How / Como',
    'how_much' => 'How much / Quanto custa',
];
?>
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
  <div>
    <p class="text-sm font-bold text-slate-500">Ticket <?= e($improvement['ticket'] ?? ('#' . (int) $improvement['id'])) ?></p>
    <h2 class="text-2xl font-black text-slate-950"><?= e($improvement['titulo']) ?></h2>
  </div>
  <a class="btn-secondary" href="<?= url('/melhorias/' . $improvement['id']) ?>"><i data-lucide="arrow-left" class="h-4 w-4"></i>Voltar</a>
</div>
<form method="post" action="<?= url('/5w2h/' . $improvement['id']) ?>" class="soft-card rounded-3xl p-6">
  <?= csrf_field() ?>
  <div class="grid gap-5 lg:grid-cols-2">
    <?php foreach ($fields as $field => $label): ?>
      <label class="<?= $field === 'how_text' ? 'lg:col-span-2' : '' ?>">
        <span class="mb-2 block text-sm font-bold text-slate-700"><?= e($label) ?></span>
        <?php if ($field === 'how_much'): ?>
          <input class="form-input" name="<?= e($field) ?>" value="<?= e($plan[$field] ?? '') ?>">
        <?php else: ?>
          <textarea class="form-textarea" name="<?= e($field) ?>"><?= e($plan[$field] ?? '') ?></textarea>
        <?php endif; ?>
      </label>
    <?php endforeach; ?>
  </div>
  <div class="mt-8 flex flex-wrap gap-3">
    <button class="btn-primary" type="submit"><i data-lucide="save" class="h-4 w-4"></i>Salvar 5W2H</button>
    <a href="<?= url('/5w2h') ?>" class="btn-secondary">Cancelar</a>
  </div>
</form>
