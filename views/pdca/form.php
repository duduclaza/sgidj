<?php 
$stages = [
    'plan' => ['label' => 'Planejar', 'color' => 'border-blue-500', 'bg' => 'bg-blue-50/30', 'icon' => 'list-todo'],
    'do' => ['label' => 'Fazer', 'color' => 'border-amber-500', 'bg' => 'bg-amber-50/30', 'icon' => 'play-circle'],
    'check' => ['label' => 'Checar', 'color' => 'border-emerald-500', 'bg' => 'bg-emerald-50/30', 'icon' => 'clipboard-check'],
    'act' => ['label' => 'Agir', 'color' => 'border-rose-500', 'bg' => 'bg-rose-50/30', 'icon' => 'zap']
]; 
?>
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-4">
        <a href="<?= url('/melhorias/' . $improvement['id']) ?>" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-400 hover:bg-slate-50 hover:text-slate-600 transition-colors">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600"><?= e($improvement['ticket']) ?></span>
                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Ciclo PDCA</span>
            </div>
            <h2 class="text-2xl font-black text-slate-900"><?= e($improvement['titulo']) ?></h2>
        </div>
    </div>
</div>

<form method="post" action="<?= url('/pdca/' . $improvement['id']) ?>">
  <?= csrf_field() ?>
  
  <div class="grid gap-6 md:grid-cols-2">
    <?php foreach ($stages as $key => $stage): ?>
      <section class="rounded-3xl border-2 <?= $stage['color'] ?> <?= $stage['bg'] ?> p-6 shadow-sm backdrop-blur-sm">
        <div class="mb-5 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="grid h-10 w-10 place-items-center rounded-xl bg-white shadow-sm">
                <i data-lucide="<?= $stage['icon'] ?>" class="h-5 w-5 <?= str_replace('border-', 'text-', $stage['color']) ?>"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900"><?= e($stage['label']) ?></h3>
          </div>
          <span class="text-xs font-bold uppercase tracking-widest text-slate-400 opacity-50"><?= strtoupper($key) ?></span>
        </div>

        <div class="space-y-4">
          <label class="block">
            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">O que fazer?</span>
            <textarea class="w-full rounded-2xl border-none bg-white/60 p-4 text-sm shadow-inner focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200 min-h-[120px]" name="<?= $key ?>_text" placeholder="Descreva as ações desta etapa..."><?= e($pdca[$key . '_text'] ?? '') ?></textarea>
          </label>

          <div class="grid gap-4 sm:grid-cols-2">
            <label>
              <span class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Status</span>
              <select class="w-full rounded-xl border-none bg-white/60 px-4 py-2.5 text-sm shadow-inner focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200" name="<?= $key ?>_status">
                <?php foreach ($stageStatuses as $status): ?>
                    <option value="<?= e($status) ?>" <?= ($pdca[$key . '_status'] ?? 'Pendente') === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                <?php endforeach; ?>
              </select>
            </label>
            <label>
              <span class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Prazo</span>
              <input class="w-full rounded-xl border-none bg-white/60 px-4 py-2 text-sm shadow-inner focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200" type="date" name="<?= $key ?>_prazo" value="<?= e($pdca[$key . '_prazo'] ?? '') ?>">
            </label>
            <label class="sm:col-span-2">
              <span class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Responsável</span>
              <select class="w-full rounded-xl border-none bg-white/60 px-4 py-2.5 text-sm shadow-inner focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200" name="<?= $key ?>_responsavel_id">
                <option value="">Selecione...</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= (int) $user['id'] ?>" <?= (int) ($pdca[$key . '_responsavel_id'] ?? 0) === (int) $user['id'] ? 'selected' : '' ?>><?= e($user['nome']) ?></option>
                <?php endforeach; ?>
              </select>
            </label>
          </div>
        </div>
      </section>
    <?php endforeach; ?>
  </div>

  <div class="mt-8 flex items-center justify-center gap-4">
    <button class="flex items-center gap-2 rounded-2xl bg-indigo-600 px-8 py-4 text-sm font-bold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-500 transition-all" type="submit">
        <i data-lucide="save" class="h-5 w-5"></i>
        Salvar Ciclo PDCA
    </button>
    <a href="<?= url('/melhorias/' . $improvement['id']) ?>" class="rounded-2xl border border-slate-200 bg-white px-8 py-4 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">Cancelar</a>
  </div>
</form>
