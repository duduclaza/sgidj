<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Status de Melhorias</h2>
        <p class="text-sm text-slate-500">Gerencie os status disponíveis para o módulo de melhorias.</p>
    </div>
    <button onclick="document.getElementById('modal-new-status').classList.remove('hidden')" class="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
        <i data-lucide="plus" class="h-4 w-4"></i>
        Novo Status
    </button>
</div>

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
            <tr>
                <th class="px-6 py-4">Ordem</th>
                <th class="px-6 py-4">Nome</th>
                <th class="px-6 py-4">Cor (Badge)</th>
                <th class="px-6 py-4 text-right">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($statuses as $status): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-600"><?= e($status['ordem']) ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full <?= e($status['cor']) ?> px-2.5 py-0.5 text-xs font-bold text-white shadow-sm">
                            <?= e($status['nome']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-500 font-mono text-xs"><?= e($status['cor']) ?></td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick='editStatus(<?= json_encode($status) ?>)' class="rounded-lg p-2 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i data-lucide="edit-3" class="h-4 w-4"></i>
                            </button>
                            <form method="post" action="<?= url('/configuracao/status/' . $status['id'] . '/excluir') ?>" onsubmit="return confirm('Excluir este status?')" class="inline">
                                <?= csrf_field() ?>
                                <button class="rounded-lg p-2 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($statuses)): ?>
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">Nenhum status cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal New Status -->
<div id="modal-new-status" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl animate-in fade-in zoom-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h3 class="text-lg font-bold text-slate-900">Novo Status</h3>
            <button onclick="document.getElementById('modal-new-status').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        <form method="post" action="<?= url('/configuracao/status') ?>" class="p-6">
            <?= csrf_field() ?>
            <div class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Nome do Status</label>
                    <input type="text" name="nome" required placeholder="Ex: Em análise" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Cor do Badge (Tailwind CSS)</label>
                    <select name="cor" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="bg-slate-500">Slate (Cinza)</option>
                        <option value="bg-blue-500">Blue (Azul)</option>
                        <option value="bg-indigo-500">Indigo (Roxo/Azul)</option>
                        <option value="bg-emerald-500">Emerald (Verde)</option>
                        <option value="bg-amber-500">Amber (Amarelo/Laranja)</option>
                        <option value="bg-red-500">Red (Vermelho)</option>
                        <option value="bg-rose-500">Rose (Rosa)</option>
                        <option value="bg-cyan-500">Cyan (Ciano)</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Ordem de Exibição</label>
                    <input type="number" name="ordem" value="0" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-new-status').classList.add('hidden')" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-indigo-500">Salvar Status</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Status -->
<div id="modal-edit-status" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl animate-in fade-in zoom-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h3 class="text-lg font-bold text-slate-900">Editar Status</h3>
            <button onclick="document.getElementById('modal-edit-status').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        <form id="form-edit-status" method="post" action="" class="p-6">
            <?= csrf_field() ?>
            <div class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Nome do Status</label>
                    <input type="text" name="nome" id="edit-nome" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Cor do Badge</label>
                    <select name="cor" id="edit-cor" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="bg-slate-500">Slate (Cinza)</option>
                        <option value="bg-blue-500">Blue (Azul)</option>
                        <option value="bg-indigo-500">Indigo (Roxo/Azul)</option>
                        <option value="bg-emerald-500">Emerald (Verde)</option>
                        <option value="bg-amber-500">Amber (Amarelo/Laranja)</option>
                        <option value="bg-red-500">Red (Vermelho)</option>
                        <option value="bg-rose-500">Rose (Rosa)</option>
                        <option value="bg-cyan-500">Cyan (Ciano)</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Ordem de Exibição</label>
                    <input type="number" name="ordem" id="edit-ordem" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-edit-status').classList.add('hidden')" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-indigo-500">Atualizar Status</button>
            </div>
        </form>
    </div>
</div>

<script>
function editStatus(status) {
    const modal = document.getElementById('modal-edit-status');
    const form = document.getElementById('form-edit-status');
    
    form.action = '<?= url('/configuracao/status/') ?>' + status.id + '/atualizar';
    document.getElementById('edit-nome').value = status.nome;
    document.getElementById('edit-cor').value = status.cor;
    document.getElementById('edit-ordem').value = status.ordem;
    
    modal.classList.remove('hidden');
}
</script>
