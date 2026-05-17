<section class="soft-card rounded-3xl p-6">
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-lg font-black text-slate-950">Central de notificações</h2>
      <p class="text-sm text-slate-500">Alertas de status, comentários e ações atribuídas.</p>
    </div>
    <i data-lucide="bell-ring" class="h-6 w-6 text-blue-600"></i>
  </div>
  <div class="mt-6 space-y-3">
    <?php foreach ($notifications as $notification): ?>
      <article class="flex flex-col gap-3 rounded-2xl border <?= (int) $notification['lida'] === 1 ? 'border-slate-200 bg-slate-50' : 'border-blue-200 bg-blue-50' ?> p-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <div class="flex items-center gap-2">
            <span class="badge <?= (int) $notification['lida'] === 1 ? 'bg-slate-200 text-slate-700' : 'bg-blue-600 text-white' ?>"><?= e($notification['tipo']) ?></span>
            <span class="text-xs font-bold text-slate-500"><?= datetime_br($notification['created_at']) ?></span>
          </div>
          <h3 class="mt-2 font-black text-slate-950"><?= e($notification['titulo']) ?></h3>
          <p class="mt-1 text-sm text-slate-600"><?= e($notification['mensagem']) ?></p>
        </div>
        <div class="flex gap-2">
          <?php if (!empty($notification['link'])): ?><a class="btn-secondary !py-2" href="<?= url($notification['link']) ?>">Abrir</a><?php endif; ?>
          <?php if ((int) $notification['lida'] === 0): ?>
            <form method="post" action="<?= url('/notificacoes/' . $notification['id'] . '/lida') ?>">
              <?= csrf_field() ?>
              <button class="btn-primary !py-2" type="submit">Marcar lida</button>
            </form>
          <?php endif; ?>
        </div>
      </article>
    <?php endforeach; ?>
    <?php if (!$notifications): ?><p class="rounded-2xl bg-slate-50 p-8 text-center text-slate-500">Nenhuma notificação por enquanto.</p><?php endif; ?>
  </div>
</section>
