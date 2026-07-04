(function () {
  function getTheme() {
    var saved = localStorage.getItem('lollipop-theme');
    if (saved === 'dark' || saved === 'light') return saved;
    return 'light'; // тема по умолчанию
  }

  function apply(theme) {
    const body = document.body;
    
    if (theme === 'dark') {
      body.classList.add('dark');
    } else {
      body.classList.remove('dark');
    }
    
    localStorage.setItem('lollipop-theme', theme);

    var btn = document.querySelector('[data-theme-toggle]');
    if (btn) {
      btn.textContent = theme === 'dark' ? '☀️' : '🌙';
      btn.title = theme === 'dark' ? 'Светлая тема' : 'Тёмная тема';
      btn.setAttribute('aria-label', btn.title);
    }
  }

  apply(getTheme());

  document.addEventListener('DOMContentLoaded', function () {
    apply(getTheme());

    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const current = getTheme();
        apply(current === 'dark' ? 'light' : 'dark');
      });
    });
  });
})();