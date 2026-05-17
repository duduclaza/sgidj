<form method="post" action="<?= url('/melhorias/' . $improvement['id'] . '/anexos') ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <input class="form-input" type="file" name="arquivo" required>
  <button class="btn-primary mt-3" type="submit">Anexar</button>
</form>
