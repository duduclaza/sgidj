<section class="grid gap-6 xl:grid-cols-[.9fr_1.1fr]">
  <form method="post" action="<?= url('/ia/sugerir') ?>" class="soft-card rounded-3xl p-6">
    <?= csrf_field() ?>
    <div class="flex items-center gap-3">
      <span class="grid h-12 w-12 place-items-center rounded-2xl bg-blue-600 text-white"><i data-lucide="brain-circuit" class="h-6 w-6"></i></span>
      <div>
        <h2 class="text-lg font-black text-slate-950">Analista de Melhoria Contínua</h2>
        <p class="text-sm text-slate-500">Preparado para integração futura via API configurada no `.env`.</p>
      </div>
    </div>

    <label class="mt-6 block">
      <span class="mb-2 block text-sm font-bold text-slate-700">Tipo de apoio</span>
      <select class="form-select" name="task">
        <option value="estrutura">Estruturar oportunidade</option>
        <option value="pdca">Sugerir PDCA</option>
        <option value="swot">Gerar SWOT</option>
        <option value="5w2h">Criar 5W2H</option>
        <option value="causa_raiz">Sugerir causa raiz</option>
      </select>
    </label>
    <label class="mt-4 block">
      <span class="mb-2 block text-sm font-bold text-slate-700">Contexto</span>
      <textarea class="form-textarea min-h-64" name="contexto" placeholder="Descreva o problema, processo, impacto, dados conhecidos e objetivo esperado."></textarea>
    </label>
    <button class="btn-primary mt-5 w-full" type="submit"><i data-lucide="sparkles" class="h-4 w-4"></i>Gerar sugestão</button>
  </form>

  <article class="soft-card rounded-3xl p-6">
    <h2 class="text-lg font-black text-slate-950">Resultado</h2>
    <?php if ($result): ?>
      <pre class="mt-4 whitespace-pre-wrap rounded-3xl bg-slate-950 p-5 text-sm leading-relaxed text-slate-50"><?= e($result) ?></pre>
    <?php else: ?>
      <div class="mt-4 rounded-3xl bg-slate-50 p-8 text-slate-600">
        <p class="font-bold text-slate-800">Sem sugestão gerada ainda.</p>
        <p class="mt-2 text-sm">Quando `AI_API_URL` e `AI_API_KEY` estiverem configurados, o service tentará usar a API. Sem essas variáveis, ele retorna uma sugestão local estruturada.</p>
      </div>
    <?php endif; ?>
  </article>
</section>
