<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <form method="get" class="flex flex-1 flex-col gap-3 sm:flex-row">
    <input class="form-input max-w-xl" name="q" value="<?= e($filters['q']) ?>" placeholder="Buscar por tema ou participante">
    <button class="btn-secondary" type="submit"><i data-lucide="search" class="h-4 w-4"></i>Filtrar</button>
  </form>
  <a href="<?= url('/reunioes/nova') ?>" class="btn-primary"><i data-lucide="calendar-plus" class="h-4 w-4"></i>Nova reunião</a>
</div>

<section class="soft-card overflow-hidden rounded-3xl">
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>Tema</th><th>Data</th><th>Participantes</th><th>Próximas ações</th><th class="text-right">Ações</th></tr></thead>
      <tbody>
      <?php foreach ($meetings as $meeting): ?>
        <tr>
          <td><strong class="text-slate-900"><?= e($meeting['tema']) ?></strong><p class="mt-1 text-sm text-slate-500"><?= e($meeting['ata_resumo']) ?></p></td>
          <td><?= date_br($meeting['data']) ?> às <?= e(substr($meeting['horario'], 0, 5)) ?></td>
          <td><?= e($meeting['participantes']) ?></td>
          <td><?= e($meeting['proximas_acoes']) ?></td>
          <td>
            <div class="flex justify-end gap-2">
              <a class="btn-secondary !p-2.5" href="<?= url('/reunioes/' . $meeting['id'] . '/editar') ?>" title="Editar"><i data-lucide="pencil" class="h-4 w-4"></i></a>
              <form method="post" action="<?= url('/reunioes/' . $meeting['id'] . '/excluir') ?>" data-confirm="Excluir esta reunião?"><?= csrf_field() ?><button class="btn-danger !p-2.5"><i data-lucide="trash-2" class="h-4 w-4"></i></button></form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$meetings): ?><tr><td colspan="5" class="text-center text-slate-500">Nenhuma reunião registrada.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
