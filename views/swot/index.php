<div class="mb-5">
  <form method="get" class="flex max-w-xl flex-col gap-3 sm:flex-row">
    <input class="form-input" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Buscar melhoria para SWOT">
    <button class="btn-secondary" type="submit"><i data-lucide="search" class="h-4 w-4"></i>Filtrar</button>
  </form>
</div>
<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
  <?php foreach ($improvements as $improvement): ?>
    <article class="soft-card rounded-3xl p-5">
      <p class="text-xs font-bold text-slate-500"><?= e($improvement['ticket'] ?? ('#' . (int) $improvement['id'])) ?> · <?= e($improvement['departamento_nome'] ?? '-') ?></p>
      <h2 class="mt-1 font-black text-slate-950"><?= e($improvement['titulo']) ?></h2>
      <a href="<?= url('/swot/' . $improvement['id'] . '/editar') ?>" class="btn-secondary mt-4 w-full">Editar SWOT</a>
    </article>
  <?php endforeach; ?>
</section>
