<section class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-8 text-center">
        <span class="inline-block px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-widest mb-2">Portal do Colaborador</span>
        <h1 class="text-3xl font-black text-slate-900"><?= e($title) ?></h1>
        <p class="mt-2 text-slate-500">Sua ideia pode transformar o nosso dia a dia. Preencha os campos abaixo.</p>
    </div>

    <form method="post" action="<?= url('/solicitar') ?>" class="space-y-6">
        <?= csrf_field() ?>
        
        <!-- Dados do Projeto -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 flex items-center gap-2 text-lg font-bold text-slate-900">
                <i data-lucide="info" class="h-5 w-5 text-indigo-500"></i>
                Dados do Projeto
            </h3>
            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Nome do Projeto / Melhoria</label>
                    <input type="text" name="titulo" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Ex: Automatização do relatório semanal">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Área / Setor</label>
                    <select name="departamento_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="">Selecione seu setor...</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['id'] ?>"><?= e($dept['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Responsável pelo Preenchimento</label>
                    <input type="text" name="responsavel_nome" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Seu nome completo">
                </div>
            </div>
        </div>

        <!-- Plano de Ação 5W2H -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 flex items-center gap-2 text-lg font-bold text-slate-900">
                <i data-lucide="list-checks" class="h-5 w-5 text-indigo-500"></i>
                Plano de Ação 5W2H
            </h3>
            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">O que será feito?</label>
                    <input type="text" name="o_que" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Quem será o responsável?</label>
                    <input type="text" name="quem" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Onde será aplicado?</label>
                    <input type="text" name="onde" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Por que essa ação é necessária?</label>
                    <textarea name="por_que" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Quando será feito?</label>
                    <input type="date" name="quando" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Quanto vai custar? (R$)</label>
                    <input type="text" name="quanto" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="0,00">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Como será executado?</label>
                    <textarea name="como" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center pt-4">
            <button type="submit" class="flex items-center gap-2 rounded-2xl bg-indigo-600 px-8 py-4 text-sm font-bold text-white shadow-lg shadow-indigo-200 hover:bg-indigo-500 hover:-translate-y-0.5 transition-all">
                <i data-lucide="send" class="h-5 w-5"></i>
                Enviar Solicitação de Melhoria
            </button>
        </div>
    </form>
</section>
