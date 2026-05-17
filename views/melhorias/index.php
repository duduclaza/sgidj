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

<!-- Grid / Table com colunas redimensionáveis -->
<style>
  #melhorias-table th {
    position: relative;
    white-space: nowrap;
    overflow: hidden;
    user-select: none;
  }
  #melhorias-table th .resizer {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    width: 5px;
    cursor: col-resize;
    background: transparent;
    z-index: 20;
  }
  #melhorias-table th .resizer:hover,
  #melhorias-table th .resizer.active {
    background: rgba(99, 102, 241, 0.5);
  }
  #melhorias-table td {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  #melhorias-table {
    table-layout: fixed;
  }
</style>

<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
    <table id="melhorias-table" class="text-left text-sm" style="width:max-content; min-width:100%">
        <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
            <tr>
                <th class="sticky left-0 z-10 bg-slate-50 px-4 py-4 shadow-[1px_0_0_0_rgba(0,0,0,0.05)]" style="min-width:180px">Ticket / Título<div class="resizer"></div></th>
                <th class="px-4 py-4" style="min-width:120px">Área / Setor<div class="resizer"></div></th>
                <th class="px-4 py-4" style="min-width:120px">Responsável<div class="resizer"></div></th>
                <th class="px-4 py-4" style="min-width:100px">Abertura<div class="resizer"></div></th>
                <th class="px-4 py-4" style="min-width:150px">O Quê?<div class="resizer"></div></th>
                <th class="px-4 py-4" style="min-width:120px">Quem?<div class="resizer"></div></th>
                <th class="px-4 py-4" style="min-width:120px">Onde?<div class="resizer"></div></th>
                <th class="px-4 py-4" style="min-width:150px">Por Quê?<div class="resizer"></div></th>
                <th class="px-4 py-4" style="min-width:100px">Quando?<div class="resizer"></div></th>
                <th class="px-4 py-4" style="min-width:150px">Como?<div class="resizer"></div></th>
                <th class="px-4 py-4" style="min-width:100px">Quanto<div class="resizer"></div></th>
                <th class="px-4 py-4 text-center" style="min-width:130px">Status<div class="resizer"></div></th>
                <th class="px-4 py-4 text-right" style="min-width:100px">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($improvements as $item): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 shadow-[1px_0_0_0_rgba(0,0,0,0.05)]">
                        <a href="<?= url('/melhorias/' . $item['id']) ?>" class="block">
                            <span class="text-xs font-bold text-indigo-600"><?= e($item['ticket']) ?></span>
                            <div class="font-bold text-slate-900 truncate" title="<?= e($item['titulo']) ?>"><?= e($item['titulo']) ?></div>
                        </a>
                    </td>
                    <td class="px-4 py-4 text-slate-600" title="<?= e($item['departamento_nome'] ?: 'N/A') ?>"><?= e($item['departamento_nome'] ?: 'N/A') ?></td>
                    <td class="px-4 py-4 text-slate-600" title="<?= e($item['responsavel_preenchimento'] ?: $item['responsavel_nome']) ?>"><?= e($item['responsavel_preenchimento'] ?: $item['responsavel_nome']) ?></td>
                    <td class="px-4 py-4 text-slate-600"><?= $item['data_abertura'] ? date('d/m/Y', strtotime($item['data_abertura'])) : '-' ?></td>

                    <!-- 5W2H -->
                    <td class="px-4 py-4 text-slate-500" title="<?= e($item['o_que']) ?>"><?= e($item['o_que']) ?></td>
                    <td class="px-4 py-4 text-slate-600" title="<?= e($item['quem']) ?>"><?= e($item['quem']) ?></td>
                    <td class="px-4 py-4 text-slate-600" title="<?= e($item['onde']) ?>"><?= e($item['onde']) ?></td>
                    <td class="px-4 py-4 text-slate-500" title="<?= e($item['por_que']) ?>"><?= e($item['por_que']) ?></td>
                    <td class="px-4 py-4 text-slate-600"><?= $item['quando'] ? date('d/m/Y', strtotime($item['quando'])) : '-' ?></td>
                    <td class="px-4 py-4 text-slate-500" title="<?= e($item['como']) ?>"><?= e($item['como']) ?></td>
                    <td class="px-4 py-4 text-slate-600">R$ <?= number_format($item['quanto'], 2, ',', '.') ?></td>
                    
                    <td class="px-4 py-4">
                        <form method="post" action="<?= url('/melhorias/' . $item['id'] . '/status') ?>" class="inline-block">
                            <?= csrf_field() ?>
                            <select name="status" onchange="this.form.submit()" class="rounded-full px-3 py-1 text-xs font-bold border-0 cursor-pointer focus:ring-2 focus:ring-indigo-500 <?= $getStatusColorClass($item['status'], $statuses) ?> text-white">
                                <?php foreach ($statuses as $s): ?>
                                    <option value="<?= e($s['nome']) ?>" <?= $item['status'] === $s['nome'] ? 'selected' : '' ?> class="bg-white text-slate-900"><?= e($s['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <a href="<?= url('/melhorias/' . $item['id'] . '/editar') ?>" class="grid place-items-center h-9 w-9 rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-600 hover:text-white hover:shadow-lg hover:shadow-indigo-100 transition-all" title="Editar">
                                <i data-lucide="edit-3" class="h-4 w-4"></i>
                            </a>
                            <form method="post" action="<?= url('/melhorias/' . $item['id'] . '/excluir') ?>" onsubmit="return confirm('ATENÇÃO: Isso excluirá PERMANENTEMENTE esta melhoria e todos os seus dados vinculados. Deseja continuar?')" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="grid place-items-center h-9 w-9 rounded-xl bg-slate-50 text-slate-400 hover:bg-red-600 hover:text-white hover:shadow-lg hover:shadow-red-100 transition-all" title="Excluir">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($improvements)): ?>
                <tr>
                    <td colspan="13" class="px-6 py-12 text-center text-slate-500">Nenhuma melhoria encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
(function () {
    const STORAGE_KEY = 'melhorias_col_widths';
    const table = document.getElementById('melhorias-table');
    if (!table) return;

    const headers = table.querySelectorAll('thead th');

    // Restaurar larguras salvas
    const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
    headers.forEach((th, i) => {
        const w = saved[i];
        if (w) th.style.width = w + 'px';
    });

    // Inicializar redimensionamento
    headers.forEach((th, index) => {
        const resizer = th.querySelector('.resizer');
        if (!resizer) return;

        let startX, startWidth;

        resizer.addEventListener('mousedown', function (e) {
            e.preventDefault();
            startX = e.pageX;
            startWidth = th.offsetWidth;
            resizer.classList.add('active');

            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        });

        function onMouseMove(e) {
            const newWidth = Math.max(60, startWidth + (e.pageX - startX));
            th.style.width = newWidth + 'px';
        }

        function onMouseUp() {
            resizer.classList.remove('active');
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);

            // Salvar todas as larguras no localStorage
            const widths = {};
            headers.forEach((h, i) => { widths[i] = h.offsetWidth; });
            localStorage.setItem(STORAGE_KEY, JSON.stringify(widths));
        }
    });
})();
</script>
