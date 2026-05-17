<form method="post" action="<?= url('/melhorias/' . $improvement['id'] . '/comentarios') ?>">
  <?= csrf_field() ?>
  <textarea class="form-textarea" name="comentario" required></textarea>
  <button class="btn-primary mt-3" type="submit">Comentar</button>
</form>
