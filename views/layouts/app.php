<?php
use App\Core\Auth;
use App\Models\Notification;

// Inicialização segura de variáveis do layout
$user = null;
$notificationCount = 0;
try {
    $user = Auth::user();
    if ($user) {
        $notificationCount = (new Notification())->unreadCount((int) $user['id']);
    }
} catch (\Throwable $e) {
    // Silently fail to allow the layout to render even if DB fails
}

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$isQualidade = in_array($currentPath, ['/melhorias', '/reunioes', '/pdca', '/swot', '/5w2h']) ||
               str_starts_with($currentPath, '/melhorias/') ||
               str_starts_with($currentPath, '/reunioes/') ||
               str_starts_with($currentPath, '/pdca/') ||
               str_starts_with($currentPath, '/swot/') ||
               str_starts_with($currentPath, '/5w2h/');
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'SGI') ?> - SGI</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS Play CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { brand: '#6366f1' },
          fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
        }
      }
    };
  </script>
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= url('assets/css/app.css') ?>">
  
  <style>
    /* Estilos críticos de emergência caso o Tailwind falhe */
    body { font-family: 'Inter', sans-serif; margin: 0; background-color: #f8fafc; }
    .sidebar { background-color: #0c1021; color: white; transition: transform 0.3s ease; }
    .nav-item:hover { background-color: rgba(255,255,255,0.05); }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 12px; font-size: 12px; }
    td { padding: 12px; border-bottom: 1px solid #e2e8f0; }
  </style>
</head>
<body class="text-slate-900">
  <div data-sidebar-close class="fixed inset-0 z-30 hidden bg-slate-950/40 backdrop-blur-sm lg:hidden"></div>

  <!-- Sidebar -->
  <aside class="sidebar fixed inset-y-0 left-0 z-40 flex flex-col border-r border-slate-800/60 bg-[#0c1021] shadow-2xl shadow-black/40">
    <!-- Brand -->
    <div class="flex items-center gap-3 px-5 pt-6 pb-2">
      <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-xs font-black text-white">SGI</span>
      <p class="text-base font-extrabold text-white tracking-tight">SGI</p>
    </div>

    <!-- Navigation -->
    <nav class="mt-5 flex-1 overflow-y-auto px-3 pb-3 sidebar-nav">

      <!-- Dashboard -->
      <a href="<?= url('/dashboard') ?>" class="nav-item flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-semibold <?= is_active('/dashboard') ? 'bg-indigo-600/15 text-indigo-400' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' ?>">
        <i data-lucide="layout-dashboard" class="h-[17px] w-[17px] shrink-0"></i>
        <span>Dashboard</span>
      </a>

      <!-- Separator -->
      <div class="my-3 mx-2 h-px bg-slate-800/60"></div>

      <!-- Qualidade Section -->
      <button type="button" class="nav-section-toggle w-full flex items-center justify-between gap-2 rounded-lg px-3 py-2 text-[11px] font-bold uppercase tracking-widest text-slate-500 hover:text-slate-300" data-toggle="qualidade">
        <span class="flex items-center gap-2">
          <i data-lucide="shield-check" class="h-3.5 w-3.5"></i>
          Qualidade
        </span>
        <i data-lucide="chevron-down" class="h-3 w-3 nav-chevron <?= $isQualidade ? 'rotate-180' : '' ?>"></i>
      </button>

      <div class="nav-section-content <?= $isQualidade ? '' : 'hidden' ?>" id="section-qualidade">
        <!-- Sub-section label -->
        <p class="mt-1 mb-1 px-3 text-[10px] font-bold uppercase tracking-widest text-indigo-500/70">Melhoria Contínua</p>

        <?php
        $qualidadeItems = [
            ['label' => 'Melhorias', 'url' => '/melhorias', 'icon' => 'sparkles', 'show' => can(['admin', 'usuario'])],
            ['label' => 'Reuniões', 'url' => '/reunioes', 'icon' => 'calendar-days', 'show' => can('admin')],
            ['label' => 'PDCA', 'url' => '/pdca', 'icon' => 'refresh-cw', 'show' => can('admin')],
            ['label' => 'SWOT', 'url' => '/swot', 'icon' => 'grid-2x2', 'show' => can('admin')],
        ];
        foreach ($qualidadeItems as $item):
          if (!$item['show']) continue;
          $active = is_active($item['url']);
        ?>
          <a href="<?= url($item['url']) ?>" class="nav-item flex items-center gap-2.5 rounded-lg px-3 py-[7px] ml-2 text-[13px] font-medium <?= $active ? 'bg-indigo-600/15 text-indigo-400' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' ?>">
            <i data-lucide="<?= e($item['icon']) ?>" class="h-[15px] w-[15px] shrink-0 <?= $active ? 'text-indigo-400' : 'text-slate-500' ?>"></i>
            <span><?= e($item['label']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>

    </nav>

    <!-- Bottom: Configurações + Logout -->
    <div class="border-t border-slate-800/60 px-3 pt-3 pb-3">
      <?php
        $isConfig = is_active('/usuarios') || is_active('/departamentos') || is_active('/relatorios');
        $showConfig = can('admin') || can('super_admin');
      ?>
      <?php if ($showConfig): ?>
      <!-- Configurações Section -->
      <button type="button" class="nav-section-toggle w-full flex items-center justify-between gap-2 rounded-lg px-3 py-2 text-[11px] font-bold uppercase tracking-widest text-slate-500 hover:text-slate-300" data-toggle="config">
        <span class="flex items-center gap-2">
          <i data-lucide="settings" class="h-3.5 w-3.5"></i>
          Configurações
        </span>
        <i data-lucide="chevron-down" class="h-3 w-3 nav-chevron <?= $isConfig ? 'rotate-180' : '' ?>"></i>
      </button>

      <div class="nav-section-content <?= $isConfig ? '' : 'hidden' ?>" id="section-config">
        <?php if (can('super_admin')): ?>
        <a href="<?= url('/usuarios') ?>" class="nav-item flex items-center gap-2.5 rounded-lg px-3 py-[7px] ml-2 text-[13px] font-medium <?= is_active('/usuarios') ? 'bg-indigo-600/15 text-indigo-400' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' ?>">
          <i data-lucide="users" class="h-[15px] w-[15px] shrink-0 <?= is_active('/usuarios') ? 'text-indigo-400' : 'text-slate-500' ?>"></i>
          <span>Usuários</span>
        </a>
        <?php endif; ?>

        <?php if (can('admin')): ?>
        <a href="<?= url('/departamentos') ?>" class="nav-item flex items-center gap-2.5 rounded-lg px-3 py-[7px] ml-2 text-[13px] font-medium <?= is_active('/departamentos') ? 'bg-indigo-600/15 text-indigo-400' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' ?>">
          <i data-lucide="building-2" class="h-[15px] w-[15px] shrink-0 <?= is_active('/departamentos') ? 'text-indigo-400' : 'text-slate-500' ?>"></i>
          <span>Departamentos</span>
        </a>
        <a href="<?= url('/configuracao/status') ?>" class="nav-item flex items-center gap-2.5 rounded-lg px-3 py-[7px] ml-2 text-[13px] font-medium <?= is_active('/configuracao/status') ? 'bg-indigo-600/15 text-indigo-400' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' ?>">
          <i data-lucide="list-checks" class="h-[15px] w-[15px] shrink-0 <?= is_active('/configuracao/status') ? 'text-indigo-400' : 'text-slate-500' ?>"></i>
          <span>Status</span>
        </a>
        <?php endif; ?>

        <?php if (can(['admin', 'usuario'], 'relatorios')): ?>
        <a href="<?= url('/relatorios') ?>" class="nav-item flex items-center gap-2.5 rounded-lg px-3 py-[7px] ml-2 text-[13px] font-medium <?= is_active('/relatorios') ? 'bg-indigo-600/15 text-indigo-400' : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200' ?>">
          <i data-lucide="file-bar-chart" class="h-[15px] w-[15px] shrink-0 <?= is_active('/relatorios') ? 'text-indigo-400' : 'text-slate-500' ?>"></i>
          <span>Relatórios</span>
        </a>
        <?php endif; ?>
      </div>

      <div class="my-2 mx-2 h-px bg-slate-800/40"></div>
      <?php endif; ?>

      <form method="post" action="<?= url('/logout') ?>">
        <?= csrf_field() ?>
        <button class="nav-item flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-semibold text-slate-400 hover:bg-red-500/10 hover:text-red-400">
          <i data-lucide="log-out" class="h-[17px] w-[17px] shrink-0"></i>
          <span>Sair</span>
        </button>
      </form>
    </div>
  </aside>

  <!-- Content -->
  <div class="content-wrap app-shell">
    <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/85 px-4 py-3 backdrop-blur-xl sm:px-6 lg:px-8">
      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <button type="button" data-sidebar-toggle class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 lg:hidden">
            <i data-lucide="menu" class="h-4 w-4"></i>
          </button>
          <div class="min-w-0">
            <h1 class="truncate text-base font-extrabold text-slate-950 sm:text-lg"><?= e($title ?? 'Sistema') ?></h1>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400">
            <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="font-medium"><?= e($user['nome'] ?? '') ?></span>
          </div>
          <a href="<?= url('/notificacoes') ?>" class="relative grid h-9 w-9 place-items-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50">
            <i data-lucide="bell" class="h-4 w-4"></i>
            <?php if ($notificationCount > 0): ?><span class="absolute -right-1 -top-1 grid h-4 min-w-4 place-items-center rounded-full bg-red-500 px-0.5 text-[9px] font-bold text-white"><?= $notificationCount ?></span><?php endif; ?>
          </a>
        </div>
      </div>
    </header>

    <main class="p-4 sm:p-6 lg:p-8">
      <?php if ($message = flash('success')): ?>
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 animate-slide-down">
          <i data-lucide="check-circle-2" class="h-4 w-4 shrink-0"></i><?= e($message) ?>
        </div>
      <?php endif; ?>
      <?php if ($message = flash('error')): ?>
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 animate-slide-down">
          <i data-lucide="alert-circle" class="h-4 w-4 shrink-0"></i><?= e($message) ?>
        </div>
      <?php endif; ?>
      <?= $content ?>
    </main>
  </div>

  <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
