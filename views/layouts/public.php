<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? config('app.name')) ?> - <?= e(config('app.name')) ?></title>
  <script>
    tailwind = { config: { theme: { extend: { colors: { brand: '#2563eb' } } } } };
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body class="public-body min-h-screen text-slate-900">
  <main class="mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-5 sm:px-6 lg:px-8">
    <header class="flex flex-wrap items-center justify-between gap-3 py-2">
      <a href="<?= url('/melhoria-publica') ?>" class="flex min-w-0 items-center gap-3">
        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-blue-600 text-sm font-black text-white shadow-lg shadow-blue-600/25">MC</span>
        <span class="min-w-0">
          <strong class="block truncate text-base font-black text-slate-950"><?= e(config('app.name')) ?></strong>
          <span class="block text-xs font-bold text-slate-500">Canal de melhorias</span>
        </span>
      </a>
      <a href="<?= url('/login') ?>" class="btn-secondary !py-2 text-sm"><i data-lucide="lock-keyhole" class="h-4 w-4"></i>Acesso interno</a>
    </header>

    <?php if ($message = flash('success')): ?>
      <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700"><?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($message = flash('error')): ?>
      <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700"><?= e($message) ?></div>
    <?php endif; ?>

    <?= $content ?>
  </main>
  <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
