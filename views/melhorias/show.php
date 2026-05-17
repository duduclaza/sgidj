<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-4">
        <a href="<?= url('/melhorias') ?>" class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 bg-white text-slate-400 hover:bg-slate-50 hover:text-slate-600 transition-colors">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
        </a>
        <div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-indigo-600"><?= e($improvement['ticket']) ?></span>
                <span class="inline-flex items-center rounded-full <?= get_status_color($improvement['status']) ?> px-2.5 py-0.5 text-[10px] font-bold text-white shadow-sm uppercase tracking-wider">
                    <?= e($improvement['status']) ?>
                </span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900"><?= e($improvement['titulo']) ?></h2>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <?php if (can('admin')): ?>
            <a href="<?= url('/melhorias/' . $improvement['id'] . '/editar') ?>" class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
                <i data-lucide="edit-3" class="h-4 w-4"></i>
                Editar
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Detalhes do Projeto -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                <i data-lucide="info" class="h-4 w-4 text-indigo-500"></i>
                Dados do Projeto
            </h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Área / Setor</span>
                    <p class="text-sm font-medium text-slate-700"><?= e($improvement['area_setor'] === 'Outro' ? $improvement['outra_area'] : $improvement['area_setor']) ?></p>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Responsável</span>
                    <p class="text-sm font-medium text-slate-700"><?= e($improvement['responsavel_preenchimento'] ?: $improvement['responsavel_nome']) ?></p>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Data de Abertura</span>
                    <p class="text-sm font-medium text-slate-700"><?= $improvement['data_abertura'] ? date('d/m/Y', strtotime($improvement['data_abertura'])) : '-' ?></p>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Prioridade</span>
                    <p class="text-sm font-medium text-slate-700"><?= e($improvement['prioridade']) ?></p>
                </div>
            </div>
        </div>

        <!-- Seção: Descrição da Melhoria -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900 uppercase tracking-tight">
                <i data-lucide="text-quote" class="h-5 w-5 text-indigo-500"></i>
                Descrição da Melhoria
            </h3>
            <div class="space-y-6">
                <div>
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Problema / Oportunidade</span>
                    <div class="text-sm text-slate-700 leading-relaxed"><?= nl2br(e($improvement['descricao_problema'])) ?></div>
                </div>
                <div>
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Solução Proposta</span>
                    <div class="text-sm text-slate-700 leading-relaxed"><?= nl2br(e($improvement['melhoria_sugerida'])) ?></div>
                </div>
                <div>
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Resultado Esperado</span>
                    <div class="text-sm text-slate-700 leading-relaxed"><?= nl2br(e($improvement['resultado_esperado'])) ?></div>
                </div>
                <?php if (!empty($improvement['observacoes'])): ?>
                <div>
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Observações</span>
                    <div class="text-sm text-slate-500 italic"><?= nl2br(e($improvement['observacoes'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Plano 5W2H -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm overflow-hidden">
            <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                <i data-lucide="list-checks" class="h-4 w-4 text-indigo-500"></i>
                Plano de Ação 5W2H
            </h3>
            <div class="space-y-4">
                <div class="grid grid-cols-4 gap-4 p-3 rounded-lg bg-slate-50">
                    <div class="col-span-1 font-bold text-xs text-slate-500 uppercase tracking-wider">O Quê?</div>
                    <div class="col-span-3 text-sm text-slate-700 font-medium"><?= nl2br(e($improvement['o_que'])) ?></div>
                </div>
                <div class="grid grid-cols-4 gap-4 p-3 rounded-lg">
                    <div class="col-span-1 font-bold text-xs text-slate-500 uppercase tracking-wider">Quem?</div>
                    <div class="col-span-3 text-sm text-slate-700 font-medium"><?= e($improvement['quem']) ?></div>
                </div>
                <div class="grid grid-cols-4 gap-4 p-3 rounded-lg bg-slate-50">
                    <div class="col-span-1 font-bold text-xs text-slate-500 uppercase tracking-wider">Onde?</div>
                    <div class="col-span-3 text-sm text-slate-700 font-medium"><?= e($improvement['onde']) ?></div>
                </div>
                <div class="grid grid-cols-4 gap-4 p-3 rounded-lg">
                    <div class="col-span-1 font-bold text-xs text-slate-500 uppercase tracking-wider">Por Quê?</div>
                    <div class="col-span-3 text-sm text-slate-700 font-medium"><?= nl2br(e($improvement['por_que'])) ?></div>
                </div>
                <div class="grid grid-cols-4 gap-4 p-3 rounded-lg bg-slate-50">
                    <div class="col-span-1 font-bold text-xs text-slate-500 uppercase tracking-wider">Quando?</div>
                    <div class="col-span-3 text-sm text-slate-700 font-medium"><?= $improvement['quando'] ? date('d/m/Y', strtotime($improvement['quando'])) : '-' ?></div>
                </div>
                <div class="grid grid-cols-4 gap-4 p-3 rounded-lg">
                    <div class="col-span-1 font-bold text-xs text-slate-500 uppercase tracking-wider">Como?</div>
                    <div class="col-span-3 text-sm text-slate-700 font-medium"><?= nl2br(e($improvement['como'])) ?></div>
                </div>
                <div class="grid grid-cols-4 gap-4 p-3 rounded-lg bg-slate-50">
                    <div class="col-span-1 font-bold text-xs text-slate-500 uppercase tracking-wider">Quanto?</div>
                    <div class="col-span-3 text-sm text-slate-700 font-bold text-indigo-600">R$ <?= number_format($improvement['quanto'], 2, ',', '.') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <!-- Evidências -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                <i data-lucide="paperclip" class="h-4 w-4 text-indigo-500"></i>
                Evidências
            </h3>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                <?php foreach ($attachments as $file): ?>
                    <div class="group relative overflow-hidden rounded-lg border border-slate-100 bg-slate-50 p-2 transition-all hover:bg-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-white shadow-sm">
                                <?php if (str_starts_with($file['tipo'], 'image/')): ?>
                                    <i data-lucide="image" class="h-5 w-5 text-indigo-500"></i>
                                <?php else: ?>
                                    <i data-lucide="file-text" class="h-5 w-5 text-indigo-500"></i>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs font-bold text-slate-900"><?= e($file['nome']) ?></p>
                                <p class="text-[10px] text-slate-500"><?= round($file['tamanho'] / 1024, 1) ?> KB</p>
                            </div>
                            <a href="<?= url('/anexos/' . $file['id'] . '/baixar') ?>" class="rounded-lg p-1.5 text-slate-400 hover:bg-indigo-600 hover:text-white transition-all">
                                <i data-lucide="download" class="h-4 w-4"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($attachments)): ?>
                    <p class="py-4 text-center text-xs text-slate-400">Nenhuma evidência anexada.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Histórico de Reuniões -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                <i data-lucide="users" class="h-4 w-4 text-indigo-500"></i>
                Histórico de Reuniões
            </h3>
            
            <div class="space-y-4">
                <?php foreach ($meetings as $meeting): ?>
                    <a href="<?= url('/reunioes/' . $meeting['id'] . '/editar') ?>" class="block rounded-lg bg-slate-50 p-3 hover:bg-slate-100 transition-colors">
                        <div class="mb-1 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-indigo-600"><?= date('d/m/Y', strtotime($meeting['data'])) ?> - <?= substr($meeting['horario'], 0, 5) ?></span>
                            <i data-lucide="external-link" class="h-3 w-3 text-slate-300"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-900 leading-tight"><?= e($meeting['tema']) ?></p>
                        <?php if (!empty($meeting['decisoes'])): ?>
                            <p class="mt-1 text-[10px] text-slate-500 line-clamp-2"><?= e($meeting['decisoes']) ?></p>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
                <?php if (empty($meetings)): ?>
                    <div class="py-6 text-center">
                        <i data-lucide="calendar-off" class="mx-auto mb-2 h-8 w-8 text-slate-200"></i>
                        <p class="text-xs text-slate-400">Nenhuma reunião vinculada.</p>
                    </div>
                <?php endif; ?>
                
                <a href="<?= url('/reunioes/nova?melhoria_id=' . $improvement['id']) ?>" class="mt-2 flex items-center justify-center gap-2 rounded-lg border border-dashed border-indigo-200 py-2 text-xs font-bold text-indigo-600 hover:bg-indigo-50 transition-colors">
                    <i data-lucide="plus" class="h-3 w-3"></i>
                    Agendar Reunião
                </a>
            </div>
        </div>

        <!-- Comentários -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                <i data-lucide="message-square" class="h-4 w-4 text-indigo-500"></i>
                Comentários
            </h3>
            
            <div class="mb-4 max-h-[300px] overflow-y-auto space-y-4 pr-1">
                <?php foreach ($comments as $comment): ?>
                    <div class="rounded-lg bg-slate-50 p-3">
                        <div class="mb-1 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-indigo-600"><?= e($comment['usuario_nome']) ?></span>
                            <span class="text-[9px] text-slate-400"><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></span>
                        </div>
                        <p class="text-xs text-slate-700 leading-relaxed"><?= nl2br(e($comment['conteudo'])) ?></p>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($comments)): ?>
                    <p class="py-4 text-center text-xs text-slate-400">Ainda não há comentários.</p>
                <?php endif; ?>
            </div>

            <form method="post" action="<?= url('/melhorias/' . $improvement['id'] . '/comentarios') ?>">
                <?= csrf_field() ?>
                <textarea name="conteudo" required placeholder="Adicionar comentário..." rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2 text-xs focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                <button type="submit" class="mt-2 w-full rounded-lg bg-indigo-600 py-2 text-xs font-bold text-white hover:bg-indigo-500 transition-colors">Enviar</button>
            </form>
        </div>
    </div>
</div>

<?php
function get_status_color($status) {
    $colors = [
        'Em planejamento' => 'bg-blue-500',
        'Em andamento' => 'bg-amber-500',
        'Finalizado' => 'bg-emerald-500',
        'Atrasado' => 'bg-red-500',
    ];
    return $colors[$status] ?? 'bg-slate-500';
}
?>
