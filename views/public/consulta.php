<section class="max-w-2xl mx-auto py-12 px-4">
    <div class="mb-10 text-center">
        <span class="inline-block px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-widest mb-2">Consulta de Ticket</span>
        <h1 class="text-3xl font-black text-slate-900">Acompanhar Melhoria</h1>
        <p class="mt-2 text-slate-500">Digite o número do seu ticket para ver o status atual.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl">
        <form method="post" action="<?= url('/melhoria-publica/consultar') ?>" class="flex gap-3">
            <?= csrf_field() ?>
            <div class="flex-1">
                <input type="text" name="ticket" required value="<?= e($lookupTicket) ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-lg font-bold uppercase tracking-wider text-slate-900 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="MEL-2026-000001">
            </div>
            <button type="submit" class="grid place-items-center rounded-2xl bg-indigo-600 px-6 text-white hover:bg-indigo-500 transition-colors">
                <i data-lucide="search" class="h-6 w-6"></i>
            </button>
        </form>

        <?php if ($lookup): ?>
            <div class="mt-10 border-t border-slate-100 pt-8">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-8">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Título do Projeto</p>
                        <h2 class="text-xl font-bold text-slate-900"><?= e($lookup['titulo']) ?></h2>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-4 py-1.5 text-xs font-bold text-indigo-600 border border-indigo-100 shadow-sm">
                        <?= e($lookup['status']) ?>
                    </span>
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Data de Abertura</p>
                        <p class="font-bold text-slate-700"><?= date('d/m/Y', strtotime($lookup['data_abertura'])) ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Última Atualização</p>
                        <p class="font-bold text-slate-700"><?= date('d/m/Y H:i', strtotime($lookup['updated_at'])) ?></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Área Responsável</p>
                        <p class="font-bold text-slate-700"><?= e($lookup['departamento_nome'] ?? 'Não informada') ?></p>
                    </div>
                </div>

                <div class="mt-8 rounded-2xl bg-slate-50 p-6 text-center">
                    <p class="text-sm text-slate-600 italic">"Sua contribuição está sendo analisada pela nossa equipe de qualidade."</p>
                </div>
            </div>
        <?php elseif ($notFound): ?>
            <div class="mt-8 rounded-2xl border border-red-100 bg-red-50 p-4 text-center">
                <p class="text-sm font-bold text-red-600">Nenhuma melhoria encontrada para o ticket "<?= e($lookupTicket) ?>".</p>
                <p class="mt-1 text-xs text-red-400">Verifique se digitou corretamente.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-8 text-center">
        <a href="<?= url('/solicitar') ?>" class="text-sm font-bold text-indigo-600 hover:underline">
            ← Voltar para o formulário de envio
        </a>
    </div>
</section>
