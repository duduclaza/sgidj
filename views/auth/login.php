<!-- Logo -->
<div class="mb-8 text-center fade-up-1">
  <div class="logo-glow mx-auto grid h-14 w-14 place-items-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600">
    <span class="text-base font-black text-white tracking-tight">SGI</span>
  </div>
</div>

<form method="post" action="<?= url('/login') ?>" class="space-y-4" id="loginForm">
  <?= csrf_field() ?>

  <div class="fade-up-2">
    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-widest text-slate-400">E-mail</label>
    <div class="auth-field">
      <input type="email" name="email" value="<?= e(old('email')) ?>" required autocomplete="email" placeholder="seu@email.com" id="login-email">
      <span class="field-icon"><i data-lucide="mail" class="h-4 w-4"></i></span>
    </div>
  </div>

  <div class="fade-up-3">
    <div class="mb-1.5 flex items-center justify-between">
      <label class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Senha</label>
      <a href="<?= url('/recuperar-senha') ?>" class="auth-link">Esqueceu?</a>
    </div>
    <div class="auth-field">
      <input type="password" name="senha" required autocomplete="current-password" placeholder="••••••••" id="login-password">
      <span class="field-icon"><i data-lucide="lock" class="h-4 w-4"></i></span>
      <button type="button" class="password-toggle" data-password-toggle="login-password" aria-label="Mostrar senha">
        <i data-lucide="eye" class="h-4 w-4"></i>
      </button>
    </div>
  </div>

  <div class="fade-up-4 pt-1">
    <button type="submit" class="login-btn" id="loginBtn">
      <span class="flex items-center justify-center gap-2" id="btnContent">
        <span>Entrar</span>
        <i data-lucide="arrow-right" class="h-4 w-4"></i>
      </span>
      <span class="hidden items-center justify-center gap-2" id="btnLoading">
        <span class="spinner"></span>
        <span>Autenticando...</span>
      </span>
    </button>
  </div>
</form>

<div class="fade-up-5 mt-5 flex items-center gap-3 before:h-px before:flex-1 before:bg-slate-700/40 after:h-px after:flex-1 after:bg-slate-700/40">
  <span class="text-[10px] font-semibold tracking-wider text-slate-500">SGI TI UAI</span>
</div>

<p class="fade-up-6 mt-3 text-center text-[10px] text-slate-500">
  <i data-lucide="shield-check" class="mr-1 inline h-3 w-3 align-[-2px]"></i>
  Ambiente protegido
</p>

<script>
  document.getElementById('loginForm').addEventListener('submit', function() {
    var btn = document.getElementById('loginBtn');
    var c = document.getElementById('btnContent');
    var l = document.getElementById('btnLoading');
    btn.disabled = true;
    c.classList.add('hidden');
    l.classList.remove('hidden');
    l.classList.add('flex');
  });
</script>
