<?php
// Helper logic for colors in this view
$getStatusColorClass = function($statusName, $statuses) {
    foreach ($statuses as $s) {
        if ($s['nome'] === $statusName) return $s['cor'];
    }
    return 'bg-slate-500';
};
?>
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Melhorias Contínuas</h2>
        <p class="text-sm text-slate-500">Gerencie todos os projetos e planos de ação de melhoria.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= url('/melhorias/nova') ?>" class="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
            <i data-lucide="plus" class="h-4 w-4"></i>
            Nova Melhoria
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="mb-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <form method="get" action="<?= url('/melhorias') ?>" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div>
            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Buscar</label>
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="q" value="<?= e($filters['q'] ?? '') ?>" placeholder="Ticket, título..." class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
        </div>
        <div>
            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Área / Setor</label>
            <select name="departamento_id" class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 px-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Todos os setores</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= ($filters['departamento_id'] ?? '') == $dept['id'] ? 'selected' : '' ?>>
                        <?= e($dept['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 px-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Todos</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= e($status['nome']) ?>" <?= ($filters['status'] ?? '') === $status['nome'] ? 'selected' : '' ?>><?= e($status['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex items-end gap-2 lg:col-span-2">
            <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition-colors">Filtrar</button>
            <a href="<?= url('/melhorias') ?>" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-400 hover:bg-slate-50 shrink-0" title="Limpar filtros">
                <i data-lucide="refresh-ccw" class="h-4 w-4"></i>
            </a>
        </div>
    </form>
</div>

<!-- Grid / Table -->
<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
    <table class="w-full min-w-[1500px] text-left text-sm">
        <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
            <tr>
                <th class="sticky left-0 z-10 bg-slate-50 px-6 py-4 shadow-[1px_0_0_0_rgba(0,0,0,0.05)]">Ticket / Título</th>
                <th class="px-6 py-4">Área / Setor</th>
                <th class="px-6 py-4">Responsável</th>
                <th class="px-6 py-4">Abertura</th>
                <th colspan="3" class="px-6 py-4 text-center border-x border-slate-200/60">Descrição da Melhoria</th>
                <th class="px-6 py-4">O Quê?</th>
                <th class="px-6 py-4">Quem?</th>
                <th class="px-6 py-4">Onde?</th>
                <th class="px-6 py-4">Por Quê?</th>
                <th class="px-6 py-4">Quando?</th>
                <th class="px-6 py-4">Como?</th>
                <th class="px-6 py-4">Quanto</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-right">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($improvements as $item): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="sticky left-0 z-10 bg-white px-6 py-4 shadow-[1px_0_0_0_rgba(0,0,0,0.05)]">
                        <a href="<?= url('/melhorias/' . $item['id']) ?>" class="block">
                            <span class="text-xs font-bold text-indigo-600"><?= e($item['ticket']) ?></span>
                            <div class="font-bold text-slate-900 truncate max-w-[200px]" title="<?= e($item['titulo']) ?>"><?= e($item['titulo']) ?></div>
                        </a>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        <?= e($item['departamento_nome'] ?: 'N/A') ?>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= e($item['responsavel_preenchimento'] ?: $item['responsavel_nome']) ?></td>
                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap"><?= $item['data_abertura'] ? date('d/m/Y', strtotime($item['data_abertura'])) : '-' ?></td>
                    
                    <!-- Acompanhamento -->
                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="<?= e($item['descricao_problema']) ?>"><?= e($item['descricao_problema']) ?></td>
                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="<?= e($item['melhoria_sugerida']) ?>"><?= e($item['melhoria_sugerida']) ?></td>
                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="<?= e($item['resultado_esperado']) ?>"><?= e($item['resultado_esperado']) ?></td>

                    <!-- 5W2H -->
                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="<?= e($item['o_que']) ?>"><?= e($item['o_que']) ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= e($item['quem']) ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= e($item['onde']) ?></td>
                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="<?= e($item['por_que']) ?>"><?= e($item['por_que']) ?></td>
                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap"><?= $item['quando'] ? date('d/m/Y', strtotime($item['quando'])) : '-' ?></td>
                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="<?= e($item['como']) ?>"><?= e($item['como']) ?></td>
                    <td class="px-6 py-4 text-slate-600">R$ <?= number_format($item['quanto'], 2, ',', '.') ?></td>
                    
                    <td class="px-6 py-4">
                        <form method="post" action="<?= url('/melhorias/' . $item['id'] . '/status') ?>" class="inline-block">
                            <?= csrf_field() ?>
                            <select name="status" onchange="this.form.submit()" class="rounded-full px-3 py-1 text-xs font-bold border-0 cursor-pointer focus:ring-2 focus:ring-indigo-500 <?= $getStatusColorClass($item['status'], $statuses) ?> text-white">
                                <?php foreach ($statuses as $s): ?>
                                    <option value="<?= e($s['nome']) ?>" <?= $item['status'] === $s['nome'] ? 'selected' : '' ?> class="bg-white text-slate-900"><?= e($s['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <a href="<?= url('/melhorias/' . $item['id'] . '/editar') ?>" class="grid place-items-center h-10 w-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-600 hover:text-white hover:shadow-lg hover:shadow-indigo-100 transition-all" title="Editar">
                                <i data-lucide="edit-3" class="h-5 w-5"></i>
                            </a>
                            <form method="post" action="<?= url('/melhorias/' . $item['id'] . '/excluir') ?>" onsubmit="return confirm('ATENÇÃO: Isso excluirá PERMANENTEMENTE esta melhoria e todos os seus dados vinculados (anexos, comentários, reuniões). Deseja continuar?')" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="grid place-items-center h-10 w-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-red-600 hover:text-white hover:shadow-lg hover:shadow-red-100 transition-all" title="Excluir">
                                    <i data-lucide="trash-2" class="h-5 w-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($improvements)): ?>
                <tr>
                    <td colspan="16" class="px-6 py-12 text-center text-slate-500">Nenhuma melhoria encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

