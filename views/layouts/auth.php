<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? config('app.name')) ?> - <?= e(config('app.name')) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script>
    tailwind = { config: { theme: { extend: {
      colors: { brand: '#6366f1' },
      fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
    } } } };
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; }

    body {
      font-family: 'Inter', system-ui, sans-serif;
      margin: 0;
      min-height: 100vh;
      overflow: hidden;
      background: #06080f;
    }

    /* Background */
    .auth-bg {
      position: fixed;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(ellipse 70% 50% at 50% -10%, rgba(99, 102, 241, 0.18), transparent),
        radial-gradient(ellipse 50% 40% at 85% 60%, rgba(14, 165, 233, 0.08), transparent),
        radial-gradient(ellipse 40% 50% at 10% 80%, rgba(168, 85, 247, 0.06), transparent),
        #06080f;
    }

    /* Geometric particles canvas */
    .geo-canvas {
      position: fixed;
      inset: 0;
      z-index: 1;
      pointer-events: none;
    }

    /* Noise */
    .noise-overlay {
      position: fixed;
      inset: 0;
      z-index: 1;
      opacity: 0.018;
      pointer-events: none;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    }

    /* ===== SQUARE LOGIN CARD ===== */
    .login-card {
      position: relative;
      width: 440px;
      height: 440px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: rgba(12, 17, 30, 0.65);
      backdrop-filter: blur(28px) saturate(140%);
      -webkit-backdrop-filter: blur(28px) saturate(140%);
      border: 1px solid rgba(99, 102, 241, 0.12);
      border-radius: 4px;
      box-shadow:
        0 0 0 1px rgba(255, 255, 255, 0.02),
        0 40px 100px rgba(0, 0, 0, 0.5),
        0 0 80px rgba(99, 102, 241, 0.04);
      animation: cardEntry 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .login-card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      padding: 1px;
      background: linear-gradient(
        180deg,
        rgba(99, 102, 241, 0.2) 0%,
        rgba(255, 255, 255, 0.03) 50%,
        rgba(99, 102, 241, 0.08) 100%
      );
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      pointer-events: none;
    }

    /* Corner accents */
    .login-card::after {
      content: '';
      position: absolute;
      top: -1px;
      left: 50%;
      transform: translateX(-50%);
      width: 60%;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.5), transparent);
    }

    @keyframes cardEntry {
      from { opacity: 0; transform: scale(0.95); filter: blur(8px); }
      to   { opacity: 1; transform: scale(1); filter: blur(0); }
    }

    /* Logo */
    .logo-glow {
      position: relative;
      animation: logoEntry 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both;
    }

    .logo-glow::after {
      content: '';
      position: absolute;
      inset: -6px;
      border-radius: inherit;
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.35), rgba(168, 85, 247, 0.25));
      filter: blur(16px);
      opacity: 0.5;
      z-index: -1;
      animation: logoPulse 4s ease-in-out infinite;
    }

    @keyframes logoEntry {
      from { opacity: 0; transform: scale(0.6); }
      to   { opacity: 1; transform: scale(1); }
    }

    @keyframes logoPulse {
      0%, 100% { opacity: 0.35; transform: scale(1); }
      50%      { opacity: 0.6; transform: scale(1.08); }
    }

    /* Inputs */
    .auth-field { position: relative; }

    .auth-field input {
      width: 100%;
      padding: 0.75rem 0.875rem 0.75rem 2.5rem;
      background: rgba(15, 23, 42, 0.5);
      border: 1px solid rgba(99, 102, 241, 0.1);
      border-radius: 3px;
      color: #f1f5f9;
      font-size: 0.875rem;
      font-family: 'Inter', system-ui, sans-serif;
      outline: none;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .auth-field input::placeholder { color: #475569; }

    .auth-field input:focus {
      border-color: rgba(99, 102, 241, 0.45);
      background: rgba(15, 23, 42, 0.75);
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1), 0 0 20px rgba(99, 102, 241, 0.04);
    }

    .auth-field input:focus + .field-icon { color: #818cf8; }

    .field-icon {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      color: #475569;
      transition: color 0.2s ease;
      pointer-events: none;
    }

    .password-toggle {
      position: absolute;
      right: 0.375rem;
      top: 50%;
      transform: translateY(-50%);
      display: grid;
      place-items: center;
      width: 2rem;
      height: 2rem;
      border-radius: 3px;
      color: #475569;
      background: transparent;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .password-toggle:hover { color: #a5b4fc; background: rgba(99, 102, 241, 0.08); }

    /* Button */
    .login-btn {
      position: relative;
      width: 100%;
      padding: 0.75rem 1.25rem;
      background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
      border: none;
      border-radius: 3px;
      color: #fff;
      font-size: 0.875rem;
      font-weight: 700;
      font-family: 'Inter', system-ui, sans-serif;
      cursor: pointer;
      overflow: hidden;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .login-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 24px rgba(99, 102, 241, 0.3), 0 0 40px rgba(99, 102, 241, 0.08);
    }

    .login-btn:active { transform: translateY(0); }
    .login-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    /* Spinner */
    .spinner {
      display: inline-block;
      width: 1rem;
      height: 1rem;
      border: 2px solid rgba(255,255,255,0.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Alerts */
    .alert-slide { animation: alertSlide 0.35s cubic-bezier(0.16, 1, 0.3, 1) both; }
    @keyframes alertSlide {
      from { opacity: 0; transform: translateY(-8px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* Staggered fade */
    .fade-up-1 { animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both; }
    .fade-up-2 { animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.3s both; }
    .fade-up-3 { animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.38s both; }
    .fade-up-4 { animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.46s both; }
    .fade-up-5 { animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.54s both; }
    .fade-up-6 { animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.62s both; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .auth-link {
      color: #818cf8;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.75rem;
      transition: color 0.2s ease;
    }
    .auth-link:hover { color: #a5b4fc; }

    /* Responsive square */
    @media (max-width: 480px) {
      .login-card {
        width: calc(100vw - 2rem);
        height: auto;
        min-height: 0;
        aspect-ratio: auto;
      }
    }

    ::-webkit-scrollbar { display: none; }
  </style>
</head>
<body>
  <div class="auth-bg"></div>
  <canvas class="geo-canvas" id="geoCanvas"></canvas>
  <div class="noise-overlay"></div>

  <main class="relative z-10 flex min-h-screen items-center justify-center p-4">
    <div class="login-card p-8">
      <?php if ($message = flash('success')): ?>
        <div class="alert-slide mb-5 flex items-center gap-2.5 rounded border border-emerald-500/20 bg-emerald-500/10 px-3 py-2.5 text-xs font-semibold text-emerald-300">
          <i data-lucide="check-circle-2" class="h-3.5 w-3.5 shrink-0"></i>
          <?= e($message) ?>
        </div>
      <?php endif; ?>
      <?php if ($message = flash('error')): ?>
        <div class="alert-slide mb-5 flex items-center gap-2.5 rounded border border-red-500/20 bg-red-500/10 px-3 py-2.5 text-xs font-semibold text-red-300">
          <i data-lucide="alert-circle" class="h-3.5 w-3.5 shrink-0"></i>
          <?= e($message) ?>
        </div>
      <?php endif; ?>
      <?= $content ?>
    </div>
  </main>

  <script src="<?= asset('js/app.js') ?>"></script>
  <script>
  (function() {
    const canvas = document.getElementById('geoCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let W, H, dpr, shapes = [];

    function resize() {
      dpr = window.devicePixelRatio || 1;
      W = window.innerWidth;
      H = window.innerHeight;
      canvas.width = W * dpr;
      canvas.height = H * dpr;
      canvas.style.width = W + 'px';
      canvas.style.height = H + 'px';
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    function init() {
      resize();
      shapes = [];
      const count = Math.min(50, Math.max(20, Math.floor(W / 30)));
      for (let i = 0; i < count; i++) {
        const type = Math.random();
        shapes.push({
          x: Math.random() * W,
          y: Math.random() * H,
          vx: (Math.random() - 0.5) * 0.3,
          vy: (Math.random() - 0.5) * 0.3,
          size: Math.random() * 8 + 3,
          rotation: Math.random() * Math.PI * 2,
          rotSpeed: (Math.random() - 0.5) * 0.008,
          opacity: Math.random() * 0.25 + 0.05,
          sides: type < 0.3 ? 3 : type < 0.5 ? 4 : type < 0.7 ? 6 : 0,
          hue: Math.random() < 0.5 ? 240 : 270
        });
      }
    }

    function drawPoly(x, y, r, sides, rot) {
      ctx.beginPath();
      for (let i = 0; i <= sides; i++) {
        const a = rot + (i * 2 * Math.PI / sides);
        const px = x + r * Math.cos(a);
        const py = y + r * Math.sin(a);
        i === 0 ? ctx.moveTo(px, py) : ctx.lineTo(px, py);
      }
      ctx.closePath();
    }

    function frame() {
      ctx.clearRect(0, 0, W, H);

      for (let i = 0; i < shapes.length; i++) {
        const s = shapes[i];
        s.x += s.vx;
        s.y += s.vy;
        s.rotation += s.rotSpeed;

        if (s.x < -20) s.x = W + 20;
        if (s.x > W + 20) s.x = -20;
        if (s.y < -20) s.y = H + 20;
        if (s.y > H + 20) s.y = -20;

        ctx.save();
        ctx.strokeStyle = `hsla(${s.hue}, 70%, 70%, ${s.opacity})`;
        ctx.lineWidth = 0.8;

        if (s.sides === 0) {
          // Circle
          ctx.beginPath();
          ctx.arc(s.x, s.y, s.size, 0, Math.PI * 2);
          ctx.stroke();
        } else {
          drawPoly(s.x, s.y, s.size, s.sides, s.rotation);
          ctx.stroke();
        }

        // Connections
        for (let j = i + 1; j < shapes.length; j++) {
          const o = shapes[j];
          const dx = s.x - o.x;
          const dy = s.y - o.y;
          const dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < 140) {
            ctx.strokeStyle = `hsla(250, 60%, 70%, ${0.06 * (1 - dist / 140)})`;
            ctx.lineWidth = 0.5;
            ctx.beginPath();
            ctx.moveTo(s.x, s.y);
            ctx.lineTo(o.x, o.y);
            ctx.stroke();
          }
        }
        ctx.restore();
      }
      requestAnimationFrame(frame);
    }

    init();
    window.addEventListener('resize', init, { passive: true });
    frame();
  })();
  </script>
</body>
</html>
