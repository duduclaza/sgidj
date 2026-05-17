<?php http_response_code(403); ?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Acesso negado</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-6">
  <main class="max-w-md rounded-3xl bg-white p-8 text-center shadow-xl shadow-slate-200">
    <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">403</p>
    <h1 class="mt-2 text-2xl font-bold text-slate-900">Acesso negado</h1>
    <p class="mt-3 text-slate-600">Seu perfil não tem permissão para acessar esta área.</p>
    <a href="<?= url('/dashboard') ?>" class="mt-6 inline-flex rounded-2xl bg-blue-600 px-5 py-3 font-semibold text-white">Voltar ao dashboard</a>
  </main>
</body>
</html>
