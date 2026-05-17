<?php
$statusColors = [
    'Aberta' => 'bg-sky-50 text-sky-700 ring-sky-200',
    'Em análise' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
    'Aprovada' => 'bg-blue-50 text-blue-700 ring-blue-200',
    'Em implantação' => 'bg-amber-50 text-amber-700 ring-amber-200',
    'Concluída' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'Cancelada' => 'bg-rose-50 text-rose-700 ring-rose-200',
];
$showLookup = $activeTab === 'consulta';
?>

<section class="grid flex-1 items-start gap-5 py-5 lg:grid-cols-[minmax(0,1fr)_330px] lg:py-8">
  <article class="public-panel rounded-[1.75rem] border border-slate-200 bg-white/92 p-4 shadow-xl shadow-slate-200/60 backdrop-blur-xl sm:p-6 lg:p-7">
    <div class="mb-5">
      <p class="text-xs font-black uppercase tracking-normal text-blue-600">Nova oportunidade</p>
      <h1 class="mt-2 text-2xl font-black text-slate-950 sm:text-3xl">Registrar melhoria</h1>
      <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Toda melhoria enviada recebe um ticket para acompanhamento do status.</p>
    </div>

    <div class="public-tabs mb-6 grid grid-cols-1 gap-2 rounded-2xl bg-slate-100 p-1 sm:grid-cols-2">
      <button type="button" class="public-tab <?= $showLookup ? '' : 'is-active' ?>" data-public-tab="formulario">
        <i data-lucide="square-pen" class="h-4 w-4"></i>Enviar melhoria
      </button>
      <button type="button" class="public-tab <?= $showLookup ? 'is-active' : '' ?>" data-public-tab="consulta">
        <i data-lucide="search-check" class="h-4 w-4"></i>Pesquisar um ticket de melhoria
      </button>
    </div>

    <div data-public-panel="formulario" class="<?= $showLookup ? 'hidden' : '' ?>">
      <form method="post" action="<?= url('/melhoria-publica') ?>" class="grid gap-5">
        <?= csrf_field() ?>

        <div class="grid gap-4 lg:grid-cols-12">
          <label class="lg:col-span-12">
            <span class="mb-2 block text-sm font-bold text-slate-700">Título</span>
            <input class="form-input" name="titulo" required value="<?= e(old('titulo')) ?>" placeholder="Ex.: Melhorar conferência de pedidos">
          </label>

          <label class="lg:col-span-5">
            <span class="mb-2 block text-sm font-bold text-slate-700">Departamento</span>
            <select class="form-select" name="departamento_id">
              <option value="">Selecione</option>
              <?php foreach ($departments as $department): ?>
                <option value="<?= (int) $department['id'] ?>" <?= (string) old('departamento_id') === (string) $department['id'] ? 'selected' : '' ?>><?= e($department['nome']) ?></option>
              <?php endforeach; ?>
            </select>
          </label>

          <label class="lg:col-span-5">
            <span class="mb-2 block text-sm font-bold text-slate-700">Seu nome</span>
            <input class="form-input" name="responsavel_nome" required value="<?= e(old('responsavel_nome')) ?>" placeholder="Seu nome">
          </label>

          <label class="lg:col-span-2">
            <span class="mb-2 block text-sm font-bold text-slate-700">Prioridade</span>
            <select class="form-select" name="prioridade">
              <?php foreach ($priorities as $priority): ?>
                <option value="<?= e($priority) ?>" <?= old('prioridade', 'Média') === $priority ? 'selected' : '' ?>><?= e($priority) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
          <label>
            <span class="mb-2 block text-sm font-bold text-slate-700">Descrição do problema</span>
            <textarea class="form-textarea min-h-40" name="descricao_problema" required placeholder="O que está acontecendo e qual o impacto?"><?= e(old('descricao_problema')) ?></textarea>
          </label>

          <label>
            <span class="mb-2 block text-sm font-bold text-slate-700">Melhoria sugerida</span>
            <textarea class="form-textarea min-h-40" name="melhoria_sugerida" placeholder="Qual mudança você sugere?"><?= e(old('melhoria_sugerida')) ?></textarea>
          </label>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
          <label>
            <span class="mb-2 block text-sm font-bold text-slate-700">Causa raiz</span>
            <textarea class="form-textarea" name="causa_raiz" placeholder="Se souber, descreva a possível causa."><?= e(old('causa_raiz')) ?></textarea>
          </label>

          <label>
            <span class="mb-2 block text-sm font-bold text-slate-700">Observações</span>
            <textarea class="form-textarea" name="observacoes" placeholder="Inclua detalhes que ajudem na análise."><?= e(old('observacoes')) ?></textarea>
          </label>
        </div>

        <button class="btn-primary w-full !rounded-2xl !py-3 sm:w-auto sm:px-6" type="submit">
          <i data-lucide="send" class="h-4 w-4"></i>Enviar melhoria
        </button>
      </form>
    </div>

    <div data-public-panel="consulta" class="<?= $showLookup ? '' : 'hidden' ?>">
      <form method="post" action="<?= url('/melhoria-publica/consultar') ?>" class="grid gap-3 sm:grid-cols-[1fr_auto]">
        <?= csrf_field() ?>
        <label>
          <span class="mb-2 block text-sm font-bold text-slate-700">Ticket</span>
          <input class="form-input uppercase" name="ticket" value="<?= e($lookupTicket) ?>" placeholder="MEL-2026-000001">
        </label>
        <div class="flex items-end">
          <button class="btn-primary w-full !rounded-2xl !py-3 sm:w-auto" type="submit">
            <i data-lucide="search" class="h-4 w-4"></i>Consultar
          </button>
        </div>
      </form>

      <?php if ($lookup): ?>
        <section class="mt-5 rounded-3xl border border-slate-200 bg-slate-50/80 p-5">
          <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
              <p class="text-xs font-black uppercase tracking-normal text-slate-500">Ticket</p>
              <h2 class="mt-1 text-2xl font-black text-slate-950"><?= e($lookup['ticket']) ?></h2>
            </div>
            <span class="badge ring-1 <?= $statusColors[$lookup['status']] ?? 'bg-slate-50 text-slate-700 ring-slate-200' ?>"><?= e($lookup['status']) ?></span>
          </div>
          <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <div>
              <p class="text-sm font-bold text-slate-500">Melhoria</p>
              <p class="mt-1 font-black text-slate-900"><?= e($lookup['titulo']) ?></p>
            </div>
            <div>
              <p class="text-sm font-bold text-slate-500">Departamento</p>
              <p class="mt-1 font-black text-slate-900"><?= e($lookup['departamento_nome'] ?? '-') ?></p>
            </div>
            <div>
              <p class="text-sm font-bold text-slate-500">Abertura</p>
              <p class="mt-1 font-black text-slate-900"><?= date_br($lookup['data_abertura']) ?></p>
            </div>
            <div>
              <p class="text-sm font-bold text-slate-500">Última atualização</p>
              <p class="mt-1 font-black text-slate-900"><?= datetime_br($lookup['updated_at']) ?></p>
            </div>
          </div>
        </section>
      <?php elseif ($notFound): ?>
        <div class="mt-5 rounded-3xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm font-bold text-amber-800">
          Nenhuma melhoria encontrada para este ticket.
        </div>
      <?php endif; ?>
    </div>
  </article>

  <aside class="public-panel rounded-[1.75rem] border border-slate-200 bg-white/90 p-5 shadow-xl shadow-slate-200/60 backdrop-blur-xl lg:sticky lg:top-5">
    <div class="flex items-center gap-3">
      <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-950 text-white">
        <i data-lucide="qr-code" class="h-5 w-5"></i>
      </span>
      <div>
        <h2 class="font-black text-slate-950">QR Code do formulário</h2>
        <p class="text-sm text-slate-500">Aponte a câmera para abrir no celular.</p>
      </div>
    </div>

    <div class="mt-5 rounded-3xl border border-slate-200 bg-white p-4">
      <img class="mx-auto aspect-square w-full max-w-56" src="<?= e($qrCodePath) ?>" alt="QR Code para o formulário público de melhoria">
    </div>

    <div class="mt-4 rounded-2xl bg-slate-50 p-3">
      <p class="text-xs font-black uppercase tracking-normal text-slate-500">Link público</p>
      <a class="mt-1 block break-all text-sm font-black text-blue-700" href="<?= e($publicUrl) ?>"><?= e($publicUrl) ?></a>
    </div>
  </aside>
</section>
