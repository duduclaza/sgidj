<?php
$isEdit = !empty($improvement);
$title = $isEdit ? 'Editar Melhoria: ' . $improvement['ticket'] : 'Nova Melhoria';
?>

<div class="mx-auto max-w-5xl">
    <form method="post" action="<?= $isEdit ? url('/melhorias/' . $improvement['id'] . '/atualizar') : url('/melhorias') ?>" enctype="multipart/form-data" class="space-y-8">
        <?= csrf_field() ?>

        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div>
                <h2 class="text-2xl font-bold text-slate-900"><?= e($title) ?></h2>
                <p class="mt-1 text-sm text-slate-500">Preencha os dados do projeto e o plano de ação detalhado.</p>
            </div>
            <div class="flex gap-3">
                <a href="<?= url('/melhorias') ?>" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm hover:bg-slate-50">Cancelar</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Salvar Melhoria</button>
            </div>
        </div>

        <!-- Dados do Projeto -->
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 flex items-center gap-2 text-lg font-bold text-slate-900">
                <i data-lucide="info" class="h-5 w-5 text-indigo-500"></i>
                Dados do Projeto
            </h3>
            
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Área / Setor</label>
                    <select name="departamento_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="">Selecione o setor...</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['id'] ?>" <?= ($improvement['departamento_id'] ?? '') == $dept['id'] ? 'selected' : '' ?>>
                                <?= e($dept['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="mt-1 text-[10px] text-slate-400 italic">Lista de setores cadastrados no sistema.</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Nome do projeto / melhoria</label>
                    <input type="text" name="titulo" value="<?= e($improvement['titulo'] ?? '') ?>" required placeholder="Ex: Checklist de Conferência" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Responsável pelo preenchimento</label>
                    <input type="text" name="responsavel_preenchimento" value="<?= e($improvement['responsavel_preenchimento'] ?? '') ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Data de abertura</label>
                    <input type="date" name="data_abertura" value="<?= e($improvement['data_abertura'] ?? date('Y-m-d')) ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>
        </section>



        <!-- Plano de Ação 5W2H -->
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 flex items-center gap-2 text-lg font-bold text-slate-900">
                <i data-lucide="list-checks" class="h-5 w-5 text-indigo-500"></i>
                Plano de Ação 5W2H
            </h3>
            
            <div class="overflow-hidden rounded-lg border border-slate-100">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3 border-b border-slate-100">Campo</th>
                            <th class="px-4 py-3 border-b border-slate-100">Pergunta</th>
                            <th class="px-4 py-3 border-b border-slate-100">Resposta</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-700">O quê?</td>
                            <td class="px-4 py-3 text-slate-500 italic">O que será feito?</td>
                            <td class="px-4 py-2">
                                <input type="text" name="o_que" value="<?= e($improvement['o_que'] ?? '') ?>" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-700">Quem?</td>
                            <td class="px-4 py-3 text-slate-500 italic">Quem será o responsável?</td>
                            <td class="px-4 py-2">
                                <input type="text" name="quem" value="<?= e($improvement['quem'] ?? '') ?>" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-700">Onde?</td>
                            <td class="px-4 py-3 text-slate-500 italic">Onde será aplicado?</td>
                            <td class="px-4 py-2">
                                <input type="text" name="onde" value="<?= e($improvement['onde'] ?? '') ?>" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-700">Por quê?</td>
                            <td class="px-4 py-3 text-slate-500 italic">Por que essa ação é necessária?</td>
                            <td class="px-4 py-2">
                                <textarea name="por_que" rows="2" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"><?= e($improvement['por_que'] ?? '') ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-700">Quando?</td>
                            <td class="px-4 py-3 text-slate-500 italic">Quando será feito?</td>
                            <td class="px-4 py-2">
                                <input type="date" name="quando" value="<?= e($improvement['quando'] ?? '') ?>" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-700">Como?</td>
                            <td class="px-4 py-3 text-slate-500 italic">Como será executado?</td>
                            <td class="px-4 py-2">
                                <textarea name="como" rows="2" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"><?= e($improvement['como'] ?? '') ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-700">Quanto?</td>
                            <td class="px-4 py-3 text-slate-500 italic">Quanto vai custar?</td>
                            <td class="px-4 py-2">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-medium text-sm">R$</span>
                                    <input type="text" name="quanto" value="<?= number_format((float)($improvement['quanto'] ?? 0), 2, ',', '.') ?>" class="w-full rounded-md border border-slate-300 bg-white pl-10 pr-3 py-1.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <label class="mb-3 block text-xs font-bold uppercase tracking-wider text-slate-500">Situação Atual (Status)</label>
                <div class="flex flex-wrap gap-4">
                    <?php 
                    $currentStatus = $improvement['status'] ?? 'Em planejamento'; 
                    foreach ($statuses as $status): ?>
                        <label class="flex items-center gap-2 cursor-pointer rounded-lg border border-slate-100 bg-slate-50 px-4 py-2 hover:bg-slate-100 transition-colors">
                            <input type="radio" name="status" value="<?= e($status['nome']) ?>" <?= $currentStatus === $status['nome'] ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-slate-700"><?= e($status['nome']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Exemplo Preenchido (Referência) -->
        <section class="rounded-xl border border-slate-200 bg-indigo-50/50 p-6 shadow-sm">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-bold text-indigo-900">
                <i data-lucide="help-circle" class="h-5 w-5"></i>
                Exemplo Preenchido
            </h3>
            <div class="overflow-hidden rounded-lg border border-indigo-100 bg-white shadow-sm">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-indigo-600 text-white" style="background-color:#4f46e5; color:#ffffff;">
                        <tr>
                            <th class="px-4 py-3" style="color:#ffffff; font-weight:700;">Campo</th>
                            <th class="px-4 py-3" style="color:#ffffff; font-weight:700;">Resposta de Exemplo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-50">
                        <tr><td class="bg-indigo-50/30 px-4 py-2 font-bold text-indigo-950">Área</td><td class="px-4 py-2 text-slate-700">Qualidade</td></tr>
                        <tr><td class="bg-indigo-50/30 px-4 py-2 font-bold text-indigo-950">O quê?</td><td class="px-4 py-2 text-slate-700">Criar checklist de conferência</td></tr>
                        <tr><td class="bg-indigo-50/30 px-4 py-2 font-bold text-indigo-950">Quem?</td><td class="px-4 py-2 text-slate-700">Supervisor de qualidade</td></tr>
                        <tr><td class="bg-indigo-50/30 px-4 py-2 font-bold text-indigo-950">Onde?</td><td class="px-4 py-2 text-slate-700">Linha de separação</td></tr>
                        <tr><td class="bg-indigo-50/30 px-4 py-2 font-bold text-indigo-950">Por quê?</td><td class="px-4 py-2 text-slate-700">Reduzir erros antes do inventário</td></tr>
                        <tr><td class="bg-indigo-50/30 px-4 py-2 font-bold text-indigo-950">Quando?</td><td class="px-4 py-2 text-slate-700">10/05/2026</td></tr>
                        <tr><td class="bg-indigo-50/30 px-4 py-2 font-bold text-indigo-950">Como?</td><td class="px-4 py-2 text-slate-700">Conferência no final da linha por equipe dedicada</td></tr>
                        <tr><td class="bg-indigo-50/30 px-4 py-2 font-bold text-indigo-950">Quanto?</td><td class="px-4 py-2 text-slate-700">Sem custo inicial</td></tr>
                        <tr><td class="bg-indigo-50/30 px-4 py-2 font-bold text-indigo-950">Status</td><td class="px-4 py-2 text-slate-700">Em andamento</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="flex justify-end gap-3 pb-10">
            <a href="<?= url('/melhorias') ?>" class="rounded-lg border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">Cancelar</a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-10 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 hover:bg-indigo-500 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                <?= $isEdit ? 'Atualizar Projeto' : 'Cadastrar Melhoria' ?>
            </button>
        </div>
    </form>
</div>
