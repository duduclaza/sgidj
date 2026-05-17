<?php
$statusColors = [
    'Aberta' => 'bg-sky-50 text-sky-700',
    'Em análise' => 'bg-indigo-50 text-indigo-700',
    'Aprovada' => 'bg-blue-50 text-blue-700',
    'Em implantação' => 'bg-amber-50 text-amber-700',
    'Concluída' => 'bg-emerald-50 text-emerald-700',
    'Cancelada' => 'bg-rose-50 text-rose-700',
];
$statusBarColors = [
    'Aberta' => '#0ea5e9',
    'Em análise' => '#6366f1',
    'Aprovada' => '#3b82f6',
    'Em implantação' => '#f59e0b',
    'Concluída' => '#10b981',
    'Cancelada' => '#f43f5e',
];
$completionRate = $stats['total'] > 0 ? round(($stats['done'] / $stats['total']) * 100) : 0;
$implantationRate = $stats['total'] > 0 ? round(($stats['implantation'] / $stats['total']) * 100) : 0;
$openRate = $stats['total'] > 0 ? round(($stats['open'] / $stats['total']) * 100) : 0;
?>

<!-- Hero KPI Row -->
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  <!-- Total -->
  <div class="metric-card relative overflow-hidden p-5">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</p>
        <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950"><?= $stats['total'] ?></p>
        <p class="mt-1 text-xs text-slate-400">melhorias registradas</p>
      </div>
      <span class="metric-icon metric-violet"><i data-lucide="bar-chart-3" class="h-5 w-5"></i></span>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
  </div>

  <!-- Abertas -->
  <div class="metric-card relative overflow-hidden p-5">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Abertas</p>
        <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950"><?= $stats['open'] ?></p>
        <p class="mt-1 text-xs text-sky-600 font-medium"><?= $openRate ?>% do total</p>
      </div>
      <span class="metric-icon metric-sky"><i data-lucide="folder-open" class="h-5 w-5"></i></span>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-sky-400 to-sky-500"></div>
  </div>

  <!-- Em Implantação -->
  <div class="metric-card relative overflow-hidden p-5">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Em Implantação</p>
        <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950"><?= $stats['implantation'] ?></p>
        <p class="mt-1 text-xs text-amber-600 font-medium"><?= $implantationRate ?>% do total</p>
      </div>
      <span class="metric-icon metric-amber"><i data-lucide="rocket" class="h-5 w-5"></i></span>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
  </div>

  <!-- Concluídas -->
  <div class="metric-card relative overflow-hidden p-5">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Concluídas</p>
        <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950"><?= $stats['done'] ?></p>
        <p class="mt-1 text-xs text-emerald-600 font-medium"><?= $completionRate ?>% efetividade</p>
      </div>
      <span class="metric-icon metric-emerald"><i data-lucide="check-circle-2" class="h-5 w-5"></i></span>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 to-green-500"></div>
  </div>
</div>

<!-- Progress bars -->
<div class="mt-5 grid gap-4 lg:grid-cols-2">
  <div class="soft-card p-5">
    <div class="flex items-center justify-between mb-3">
      <div class="flex items-center gap-2">
        <i data-lucide="target" class="h-4 w-4 text-emerald-500"></i>
        <span class="text-sm font-bold text-slate-700">Taxa de Conclusão</span>
      </div>
      <span class="text-2xl font-extrabold text-slate-950"><?= $completionRate ?>%</span>
    </div>
    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
      <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 transition-all duration-700" style="width: <?= $completionRate ?>%"></div>
    </div>
    <p class="mt-2 text-[11px] text-slate-400"><?= $stats['done'] ?> de <?= $stats['total'] ?> melhorias concluídas</p>
  </div>

  <div class="soft-card p-5">
    <div class="flex items-center justify-between mb-3">
      <div class="flex items-center gap-2">
        <i data-lucide="activity" class="h-4 w-4 text-indigo-500"></i>
        <span class="text-sm font-bold text-slate-700">Taxa de Implantação</span>
      </div>
      <span class="text-2xl font-extrabold text-slate-950"><?= $implantationRate ?>%</span>
    </div>
    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
      <div class="h-full rounded-full bg-gradient-to-r from-indigo-400 to-indigo-500 transition-all duration-700" style="width: <?= $implantationRate ?>%"></div>
    </div>
    <p class="mt-2 text-[11px] text-slate-400"><?= $stats['implantation'] ?> de <?= $stats['total'] ?> em fase de implantação</p>
  </div>
</div>

<!-- Charts row -->
<div class="mt-5 grid gap-5 xl:grid-cols-[1.3fr_0.7fr]">
  <!-- Area chart -->
  <div class="soft-card p-5">
    <div class="flex items-center justify-between mb-1">
      <div>
        <h2 class="text-sm font-bold text-slate-800">Evolução Mensal</h2>
        <p class="text-xs text-slate-400">Volume de melhorias abertas por mês</p>
      </div>
      <span class="inline-flex items-center gap-1.5 rounded-md bg-indigo-50 border border-indigo-100 px-2 py-1 text-[10px] font-bold text-indigo-600">
        <i data-lucide="trending-up" class="h-3 w-3"></i> Tendência
      </span>
    </div>
    <canvas class="mt-4 h-56 w-full" data-chart-type="area" data-chart='<?= e(json_encode($stats['monthly'], JSON_UNESCAPED_UNICODE)) ?>'></canvas>
  </div>

  <!-- Status breakdown -->
  <div class="soft-card p-5">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-sm font-bold text-slate-800">Distribuição por Status</h2>
        <p class="text-xs text-slate-400">Estado atual das melhorias</p>
      </div>
      <i data-lucide="pie-chart" class="h-4 w-4 text-slate-300"></i>
    </div>
    <div class="space-y-2.5">
      <?php foreach ($stats['byStatus'] as $row): ?>
        <?php
          $percent = $stats['total'] > 0 ? round(((int) $row['total'] / $stats['total']) * 100) : 0;
          $barColor = $statusBarColors[$row['status']] ?? '#94a3b8';
        ?>
        <div>
          <div class="flex items-center justify-between mb-1">
            <span class="text-xs font-semibold text-slate-600"><?= e($row['status']) ?></span>
            <span class="text-xs font-bold text-slate-800"><?= (int) $row['total'] ?> <span class="text-slate-400 font-normal">(<?= $percent ?>%)</span></span>
          </div>
          <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full transition-all duration-500" style="width: <?= $percent ?>%; background: <?= $barColor ?>"></div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (!$stats['byStatus']): ?>
        <div class="flex flex-col items-center gap-2 py-8">
          <i data-lucide="inbox" class="h-6 w-6 text-slate-300"></i>
          <p class="text-xs text-slate-400">Nenhuma melhoria cadastrada</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Bottom row -->
<div class="mt-5 grid gap-5 xl:grid-cols-2">
  <!-- Department ranking -->
  <div class="soft-card p-5">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-sm font-bold text-slate-800">Ranking de Departamentos</h2>
        <p class="text-xs text-slate-400">Áreas com mais iniciativas</p>
      </div>
      <i data-lucide="award" class="h-4 w-4 text-amber-400"></i>
    </div>
    <div class="space-y-3">
      <?php foreach ($stats['byDepartment'] as $i => $row): ?>
        <?php $percent = min(100, ((int) $row['total'] / max(1, (int) $stats['total'])) * 100); ?>
        <div class="flex items-center gap-3">
          <span class="grid h-6 w-6 shrink-0 place-items-center rounded text-[10px] font-bold <?= $i === 0 ? 'bg-amber-50 text-amber-600' : ($i === 1 ? 'bg-slate-100 text-slate-500' : 'bg-slate-50 text-slate-400') ?>"><?= $i + 1 ?></span>
          <div class="flex-1 min-w-0">
            <div class="flex justify-between items-center mb-1">
              <span class="text-xs font-semibold text-slate-700 truncate"><?= e($row['nome']) ?></span>
              <span class="text-xs font-bold text-slate-800 ml-2"><?= (int) $row['total'] ?></span>
            </div>
            <div class="h-1 overflow-hidden rounded-full bg-slate-100">
              <div class="h-full rounded-full bg-slate-700" style="width: <?= $percent ?>%"></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (!$stats['byDepartment']): ?>
        <div class="flex flex-col items-center gap-2 py-6">
          <i data-lucide="building-2" class="h-6 w-6 text-slate-300"></i>
          <p class="text-xs text-slate-400">Nenhum departamento registrado</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Team & Engagement -->
  <div class="space-y-5">
    <!-- Quick Actions / Posters -->
    <div class="soft-card p-5 bg-gradient-to-br from-indigo-600 to-purple-700 text-white border-none">
      <div class="flex items-start justify-between mb-4">
        <div>
          <h2 class="text-sm font-bold">Engajamento da Equipe</h2>
          <p class="text-[11px] opacity-80 mt-1">Gere cartazes com QR Codes para espalhar pela empresa e coletar novas ideias.</p>
        </div>
        <i data-lucide="megaphone" class="h-5 w-5 opacity-40"></i>
      </div>
      <a href="<?= url('/configuracao/cartazes') ?>" class="flex items-center justify-center gap-2 rounded-xl bg-white/10 py-3 text-xs font-bold backdrop-blur-md hover:bg-white/20 transition-all">
        <i data-lucide="printer" class="h-3.5 w-3.5"></i>
        Gerar Cartazes para Impressão
      </a>
    </div>

    <!-- Team -->
    <div class="soft-card p-5">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-sm font-bold text-slate-800">Equipe Ativa</h2>
        <p class="text-xs text-slate-400">Usuários habilitados no sistema</p>
      </div>
      <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 border border-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-600">
        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
        <?= count($users) ?> ativos
      </span>
    </div>
    <div class="space-y-2">
      <?php foreach (array_slice($users, 0, 6) as $member): ?>
        <div class="flex items-center gap-3 rounded-lg p-2 hover:bg-slate-50 transition-colors">
          <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-gradient-to-br from-indigo-100 to-purple-100 text-[10px] font-bold text-indigo-600">
            <?= strtoupper(mb_substr($member['nome'], 0, 2)) ?>
          </div>
          <div class="min-w-0 flex-1">
            <p class="truncate text-xs font-semibold text-slate-700"><?= e($member['nome']) ?></p>
            <p class="truncate text-[10px] text-slate-400 capitalize"><?= e($member['perfil']) ?></p>
          </div>
          <span class="flex items-center gap-1 text-[10px] font-medium <?= $member['status'] === 'ativo' ? 'text-emerald-500' : 'text-slate-400' ?>">
            <span class="h-1.5 w-1.5 rounded-full <?= $member['status'] === 'ativo' ? 'bg-emerald-500' : 'bg-slate-300' ?>"></span>
            <?= e($member['status']) ?>
          </span>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if (can('super_admin')): ?>
      <a href="<?= url('/usuarios') ?>" class="mt-3 flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 py-2 text-xs font-semibold text-slate-500 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-colors">
        <i data-lucide="settings" class="h-3.5 w-3.5"></i>
        Gerenciar Usuários
      </a>
    <?php endif; ?>
  </div>
</div>
