document.addEventListener('DOMContentLoaded', () => {
  // Initialize Lucide icons
  if (window.lucide) {
    window.lucide.createIcons();
  }

  // Password toggle
  document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
      const input = document.getElementById(button.dataset.passwordToggle || '');
      if (!input) return;
      const visible = input.type === 'text';
      input.type = visible ? 'password' : 'text';
      button.setAttribute('aria-label', visible ? 'Mostrar senha' : 'Ocultar senha');
      button.innerHTML = `<i data-lucide="${visible ? 'eye' : 'eye-off'}" class="h-4 w-4"></i>`;
      if (window.lucide) window.lucide.createIcons();
    });
  });

  // Public tabs
  document.querySelectorAll('[data-public-tab]').forEach((button) => {
    button.addEventListener('click', () => {
      const target = button.dataset.publicTab || 'formulario';
      document.querySelectorAll('[data-public-tab]').forEach((tab) => {
        tab.classList.toggle('is-active', tab.dataset.publicTab === target);
      });
      document.querySelectorAll('[data-public-panel]').forEach((panel) => {
        panel.classList.toggle('hidden', panel.dataset.publicPanel !== target);
      });
    });
  });

  // Nav section toggle (Qualidade submenu)
  document.querySelectorAll('[data-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
      const target = document.getElementById('section-' + button.dataset.toggle);
      if (!target) return;
      const chevron = button.querySelector('.nav-chevron');
      target.classList.toggle('hidden');
      if (chevron) chevron.classList.toggle('rotate-180');
    });
  });

  // Sidebar mobile toggle
  document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
      document.body.classList.toggle('sidebar-open');
    });
  });

  document.querySelectorAll('[data-sidebar-close]').forEach((el) => {
    el.addEventListener('click', () => document.body.classList.remove('sidebar-open'));
  });

  // Confirm dialogs
  document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!confirm(form.dataset.confirm || 'Confirmar ação?')) {
        event.preventDefault();
      }
    });
  });

  // Auth particles (geometric)
  document.querySelectorAll('canvas[data-particles]').forEach((canvas) => {
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const particles = [];
    let width = 0;
    let height = 0;
    let ratio = window.devicePixelRatio || 1;

    const resize = () => {
      ratio = window.devicePixelRatio || 1;
      width = window.innerWidth;
      height = window.innerHeight;
      canvas.width = width * ratio;
      canvas.height = height * ratio;
      canvas.style.width = `${width}px`;
      canvas.style.height = `${height}px`;
      ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
      const count = Math.min(80, Math.max(30, Math.floor(width / 22)));
      particles.length = 0;
      for (let i = 0; i < count; i += 1) {
        particles.push({
          x: Math.random() * width,
          y: Math.random() * height,
          vx: (Math.random() - 0.5) * 0.25,
          vy: (Math.random() - 0.5) * 0.25,
          r: Math.random() * 1.5 + 0.6,
        });
      }
    };

    const draw = () => {
      ctx.clearRect(0, 0, width, height);
      ctx.fillStyle = 'rgba(147, 197, 253, 0.55)';
      particles.forEach((p, index) => {
        p.x += p.vx;
        p.y += p.vy;
        if (p.x < 0 || p.x > width) p.vx *= -1;
        if (p.y < 0 || p.y > height) p.vy *= -1;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fill();

        for (let j = index + 1; j < particles.length; j += 1) {
          const other = particles[j];
          const dx = p.x - other.x;
          const dy = p.y - other.y;
          const distance = Math.sqrt(dx * dx + dy * dy);
          if (distance < 110) {
            ctx.strokeStyle = `rgba(96, 165, 250, ${0.1 * (1 - distance / 110)})`;
            ctx.lineWidth = 0.8;
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(other.x, other.y);
            ctx.stroke();
          }
        }
      });
      requestAnimationFrame(draw);
    };

    resize();
    window.addEventListener('resize', resize, { passive: true });
    draw();
  });

  // Charts
  document.querySelectorAll('canvas[data-chart]').forEach((canvas) => {
    const data = JSON.parse(canvas.dataset.chart || '[]');
    const ctx = canvas.getContext('2d');
    if (!ctx || data.length === 0) return;

    const rect = canvas.getBoundingClientRect();
    const ratio = window.devicePixelRatio || 1;
    canvas.width = rect.width * ratio;
    canvas.height = rect.height * ratio;
    ctx.scale(ratio, ratio);

    const width = rect.width;
    const height = rect.height;
    const type = canvas.dataset.chartType || 'bar';
    const max = Math.max(...data.map((item) => Number(item.total || 0)), 1);
    const padding = 24;
    const usableWidth = width - padding * 2;

    ctx.clearRect(0, 0, width, height);
    ctx.font = '11px Inter, system-ui';

    if (type === 'area') {
      const points = data.map((item, index) => {
        const x = padding + (data.length === 1 ? usableWidth / 2 : (usableWidth / (data.length - 1)) * index);
        const y = height - 34 - ((height - 76) * Number(item.total || 0)) / max;
        return { x, y, label: item.mes || item.nome || item.status || '', value: Number(item.total || 0) };
      });

      // Grid lines
      ctx.strokeStyle = 'rgba(226, 232, 240, 0.7)';
      ctx.lineWidth = 1;
      for (let i = 0; i < 4; i += 1) {
        const y = 22 + ((height - 62) / 3) * i;
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(width - padding, y);
        ctx.stroke();
      }

      // Area fill
      const area = ctx.createLinearGradient(0, 20, 0, height - 30);
      area.addColorStop(0, 'rgba(79, 70, 229, 0.15)');
      area.addColorStop(1, 'rgba(79, 70, 229, 0.01)');

      ctx.beginPath();
      points.forEach((point, index) => {
        if (index === 0) ctx.moveTo(point.x, point.y);
        else ctx.lineTo(point.x, point.y);
      });
      ctx.lineTo(points[points.length - 1].x, height - 34);
      ctx.lineTo(points[0].x, height - 34);
      ctx.closePath();
      ctx.fillStyle = area;
      ctx.fill();

      // Line
      ctx.beginPath();
      points.forEach((point, index) => {
        if (index === 0) ctx.moveTo(point.x, point.y);
        else ctx.lineTo(point.x, point.y);
      });
      ctx.strokeStyle = '#4f46e5';
      ctx.lineWidth = 2.5;
      ctx.lineJoin = 'round';
      ctx.stroke();

      // Dots
      points.forEach((point) => {
        ctx.fillStyle = '#fff';
        ctx.beginPath();
        ctx.arc(point.x, point.y, 4, 0, Math.PI * 2);
        ctx.fill();
        ctx.strokeStyle = '#4f46e5';
        ctx.lineWidth = 2;
        ctx.stroke();
        ctx.fillStyle = '#94a3b8';
        ctx.fillText(String(point.label), point.x - 16, height - 12);
      });
      return;
    }

    // Bar chart
    const barWidth = Math.max(16, usableWidth / data.length - 8);
    data.forEach((item, index) => {
      const value = Number(item.total || 0);
      const barHeight = ((height - 64) * value) / max;
      const x = padding + index * (barWidth + 8);
      const y = height - 34 - barHeight;
      const gradient = ctx.createLinearGradient(0, y, 0, height);
      gradient.addColorStop(0, '#4f46e5');
      gradient.addColorStop(1, '#c7d2fe');
      ctx.fillStyle = gradient;
      ctx.beginPath();
      ctx.roundRect(x, y, barWidth, barHeight, 6);
      ctx.fill();
      ctx.fillStyle = '#94a3b8';
      ctx.fillText(String(item.mes || item.nome || item.status || ''), x, height - 12, barWidth + 12);
    });
  });
});
