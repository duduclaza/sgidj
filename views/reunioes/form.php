<?php $selected = array_filter(array_map('trim', explode(',', (string) ($meeting['melhorias_discutidas'] ?? '')))); ?>
<form method="post" action="<?= $meeting ? url('/reunioes/' . $meeting['id'] . '/atualizar') : url('/reunioes') ?>" class="soft-card rounded-3xl p-6">
  <?= csrf_field() ?>
  <div class="grid gap-5 lg:grid-cols-4">
    <label class="lg:col-span-4">
      <span class="mb-2 block text-sm font-bold text-slate-700">Vincular a uma Melhoria (Obrigatório)</span>
      <select class="form-select" name="melhoria_id" required>
        <option value="">Selecione a melhoria...</option>
        <?php foreach ($improvements as $improvement): ?>
          <option value="<?= $improvement['id'] ?>" <?= (old('melhoria_id', $meeting['melhoria_id'] ?? '') == $improvement['id']) ? 'selected' : '' ?>>
            <?= e(($improvement['ticket'] ?? '#' . $improvement['id']) . ' - ' . $improvement['titulo']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <label class="lg:col-span-4">
      <span class="mb-2 block text-sm font-bold text-slate-700">Tema da Reunião</span>
      <input class="form-input" name="tema" required value="<?= e(old('tema', $meeting['tema'] ?? '')) ?>" placeholder="Ex: Definição de prazos para o projeto X">
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Data</span>
      <input class="form-input" type="date" name="data" value="<?= e(old('data', $meeting['data'] ?? date('Y-m-d'))) ?>">
    </label>
    <label>
      <span class="mb-2 block text-sm font-bold text-slate-700">Horário</span>
      <input class="form-input" type="time" name="horario" value="<?= e(old('horario', isset($meeting['horario']) ? substr($meeting['horario'], 0, 5) : date('H:i'))) ?>">
    </label>
    <label class="lg:col-span-2">
      <span class="mb-2 block text-sm font-bold text-slate-700">Participantes</span>
      <input class="form-input" name="participantes" value="<?= e(old('participantes', $meeting['participantes'] ?? '')) ?>" placeholder="Nomes separados por vírgula">
    </label>
    <label class="lg:col-span-2">
      <span class="mb-2 block text-sm font-bold text-slate-700">Decisões</span>
      <textarea class="form-textarea" name="decisoes"><?= e(old('decisoes', $meeting['decisoes'] ?? '')) ?></textarea>
    </label>
    <label class="lg:col-span-2">
      <span class="mb-2 block text-sm font-bold text-slate-700">Próximas ações</span>
      <textarea class="form-textarea" name="proximas_acoes"><?= e(old('proximas_acoes', $meeting['proximas_acoes'] ?? '')) ?></textarea>
    </label>
    <label class="lg:col-span-4">
      <span class="mb-2 block text-sm font-bold text-slate-700">Ata/resumo da reunião</span>
      <textarea class="form-textarea" name="ata_resumo"><?= e(old('ata_resumo', $meeting['ata_resumo'] ?? '')) ?></textarea>
    </label>
  </div>
  <div class="mt-8 flex flex-wrap gap-3">
    <button class="btn-primary" type="submit"><i data-lucide="save" class="h-4 w-4"></i>Salvar</button>
    <a class="btn-secondary" href="<?= url('/reunioes') ?>">Cancelar</a>
  </div>
</form>
