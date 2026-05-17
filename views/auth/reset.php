<!-- Logo -->
<div class="mb-6 text-center fade-up-1">
  <div class="logo-glow mx-auto grid h-12 w-12 place-items-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600">
    <i data-lucide="key-round" class="h-5 w-5 text-white"></i>
  </div>
  <h1 class="mt-4 text-xl font-extrabold tracking-tight text-white">Redefinir senha</h1>
  <p class="mt-1 text-xs text-slate-400">Crie uma nova senha para sua conta</p>
</div>

<form method="post" action="<?= url("/redefinir-senha") ?>" class="space-y-4" id="resetForm">
  <?= csrf_field() ?>

  <div class="fade-up-2">
    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-widest text-slate-400">E-mail</label>
    <div class="auth-field">
      <input type="email" name="email" required autocomplete="email" placeholder="seu@email.com" id="reset-email" value="<?= e($email ?? '') ?>" <?= !empty($email) ? 'readonly class="opacity-75"' : '' ?>>
      <span class="field-icon"><i data-lucide="mail" class="h-4 w-4"></i></span>
    </div>
  </div>

  <div class="fade-up-2 pt-2">
    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-widest text-slate-400">Código de 6 dígitos</label>
    <div class="auth-field">
      <input type="text" name="codigo" required placeholder="000000" id="reset-code" maxlength="6" pattern="\d{6}" class="tracking-widest">
      <span class="field-icon"><i data-lucide="hash" class="h-4 w-4"></i></span>
    </div>
  </div>

  <div class="fade-up-2 pt-2">
    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-widest text-slate-400">Nova Senha</label>
    <div class="auth-field">
      <input type="password" name="senha" required autocomplete="new-password" placeholder="Mínimo 6 caracteres" id="reset-password">
      <span class="field-icon"><i data-lucide="lock" class="h-4 w-4"></i></span>
    </div>
  </div>

  <div class="fade-up-2 pt-2">
    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-widest text-slate-400">Confirme a Nova Senha</label>
    <div class="auth-field">
      <input type="password" name="senha_confirmacao" required autocomplete="new-password" placeholder="Repita a senha" id="reset-password-confirm">
      <span class="field-icon"><i data-lucide="lock" class="h-4 w-4"></i></span>
    </div>
  </div>

  <div class="fade-up-3 pt-3">
    <button type="submit" class="login-btn" id="resetBtn">
      <span class="flex items-center justify-center gap-2" id="resetBtnContent">
        <span>Redefinir Senha</span>
        <i data-lucide="check" class="h-4 w-4"></i>
      </span>
      <span class="hidden items-center justify-center gap-2" id="resetBtnLoading">
        <span class="spinner"></span>
        <span>Redefinindo...</span>
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
  document.getElementById('resetForm').addEventListener('submit', function() {
    var btn = document.getElementById('resetBtn');
    var c = document.getElementById('resetBtnContent');
    var l = document.getElementById('resetBtnLoading');
    btn.disabled = true;
    c.classList.add('hidden');
    l.classList.remove('hidden');
    l.classList.add('flex');
  });
</script>
