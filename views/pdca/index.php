<div class="mb-5">
  <form method="get" class="flex max-w-xl flex-col gap-3 sm:flex-row">
    <input class="form-input" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Buscar melhoria para PDCA">
    <button class="btn-secondary" type="submit"><i data-lucide="search" class="h-4 w-4"></i>Filtrar</button>
  </form>
</div>
<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
  <?php foreach ($improvements as $improvement): ?>
    <article class="soft-card rounded-3xl p-5">
      <div class="flex items-start justify-between gap-3">
        <div>
          <p class="text-xs font-bold text-slate-500"><?= e($improvement['ticket'] ?? ('#' . (int) $improvement['id'])) ?> · <?= e($improvement['status']) ?></p>
          <h2 class="mt-1 font-black text-slate-950"><?= e($improvement['titulo']) ?></h2>
          <p class="mt-2 line-clamp-2 text-sm text-slate-500"><?= e($improvement['descricao_problema']) ?></p>
        </div>
        <i data-lucide="refresh-cw" class="h-5 w-5 text-blue-600"></i>
      </div>
      <a href="<?= url('/pdca/' . $improvement['id'] . '/editar') ?>" class="btn-secondary mt-4 w-full">Editar PDCA</a>
    </article>
  <?php endforeach; ?>
  <?php if (!$improvements): ?><p class="soft-card rounded-3xl p-6 text-slate-500">Nenhuma melhoria encontrada.</p><?php endif; ?>
</section>
