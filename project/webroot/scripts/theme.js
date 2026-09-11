(function () {
  function getTheme() {
    var saved = localStorage.getItem('lollipop-theme');
    if (saved === 'dark' || saved === 'light') return saved;
    return 'light';
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
      // btn.textContent = theme === 'dark' ? '☀️' : '🌙';
      btn.innerHTML = theme === 'dark' ?
        `<svg  xmlns="http://www.w3.org/2000/svg" width="22" height="22"  
fill="currentColor" viewBox="0 0 24 24" >
  <path d="M12 6.99a5.01 5.01 0 1 0 0 10.02 5.01 5.01 0 1 0 0-10.02M13 19h-2v3h2zm0-17h-2v3h2zM2 11h3v2H2zm17 0h3v2h-3zM4.22 18.36l.71.71.71.71 1.06-1.06 1.06-1.06-.71-.71-.71-.71-1.06 1.06zM19.78 5.64l-.71-.71-.71-.71-1.06 1.06-1.06 1.06.71.71.71.71 1.06-1.06zm-12.02.7L6.7 5.28 5.64 4.22l-.71.71-.71.71L5.28 6.7l1.06 1.06.71-.71zm8.48 11.32 1.06 1.06 1.06 1.06.71-.71.71-.71-1.06-1.06-1.06-1.06-.71.71z"></path>
</svg>` 
      : 
        `<svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20"  
fill="currentColor" viewBox="0 0 24 24" >
  <path d="M12.2 22c4.53 0 8.45-2.91 9.76-7.24.11-.35.01-.74-.25-1s-.64-.36-1-.25c-.78.23-1.58.35-2.38.35-4.52 0-8.2-3.68-8.2-8.2 0-.8.12-1.6.35-2.38a1.002 1.002 0 0 0-1.25-1.25A10.17 10.17 0 0 0 2 11.8C2 17.42 6.58 22 12.2 22"></path>
</svg>`;
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