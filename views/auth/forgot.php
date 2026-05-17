<!-- Logo -->
<div class="mb-6 text-center fade-up-1">
  <div class="logo-glow mx-auto grid h-12 w-12 place-items-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600">
    <i data-lucide="key-round" class="h-5 w-5 text-white"></i>
  </div>
  <h1 class="mt-4 text-xl font-extrabold tracking-tight text-white">Recuperar senha</h1>
  <p class="mt-1 text-xs text-slate-400">Informe seu e-mail para solicitar a recuperação</p>
</div>

<form method="post" action="<?= url('/recuperar-senha') ?>" class="space-y-4" id="forgotForm">
  <?= csrf_field() ?>

  <div class="fade-up-2">
    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-widest text-slate-400">E-mail</label>
    <div class="auth-field">
      <input type="email" name="email" required autocomplete="email" placeholder="seu@email.com" id="forgot-email">
      <span class="field-icon"><i data-lucide="mail" class="h-4 w-4"></i></span>
    </div>
  </div>

  <div class="fade-up-3 pt-1">
    <button type="submit" class="login-btn" id="forgotBtn">
      <span class="flex items-center justify-center gap-2" id="forgotBtnContent">
        <span>Solicitar recuperação</span>
        <i data-lucide="send" class="h-4 w-4"></i>
      </span>
      <span class="hidden items-center justify-center gap-2" id="forgotBtnLoading">
        <span class="spinner"></span>
        <span>Enviando...</span>
      </span>
    </button>
  </div>
</form>

<div class="fade-up-4 mt-6 text-center">
  <a href="<?= url('/login') ?>" class="auth-link inline-flex items-center gap-1.5">
    <i data-lucide="arrow-left" class="h-3 w-3"></i>
    Voltar para login
  </a>
</div>

<script>
  document.getElementById('forgotForm').addEventListener('submit', function() {
    var btn = document.getElementById('forgotBtn');
    var c = document.getElementById('forgotBtnContent');
    var l = document.getElementById('forgotBtnLoading');
    btn.disabled = true;
    c.classList.add('hidden');
    l.classList.remove('hidden');
    l.classList.add('flex');
  });
</script>
