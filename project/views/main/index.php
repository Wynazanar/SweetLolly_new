<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Главная | SweetLolly</title>
  <link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/reset.css">
  <link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/global.css">
  <link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/main.css">
</head>

<body>
  <main>
    <div class="container">
      <section class="beauty-hero">
        <span class="beauty-eyebrow"><span class="status-dot"></span> Сервер открыт</span>
        <h1>Добро пожаловать на SweetLolly</h1>
        <p>Красивый Minecraft-проект с режимами, форумом, помощью, личным кабинетом и собственной валютой. Всё собрано в
          одном аккуратном сайте.</p>

        <div class="copy-box">
          <div>
            <small class="muted">IP сервера</small><br>
            <code class="ip-value">play.swetlolly.net</code>
          </div>
          <button data-copy="play.swetlolly.net">Скопировать IP</button>
        </div>
        <script>
          document.addEventListener('DOMContentLoaded', () => {
            const ip = 'play.swetlolly.net';          // ← твой IP
            const $ip = document.querySelector('.ip-value');
            const $button = document.querySelector('[data-copy]');

            // Копирование
            $button.addEventListener('click', () => {
              navigator.clipboard.writeText(ip).then(() => {
                const original = $button.textContent;
                $button.textContent = 'Скопировано!';
                setTimeout(() => $button.textContent = original, 2000);
              });
            });

            // Эффект печатания
            function writeIp(text, callback) {
              let current = '';
              $ip.textContent = '';

              for (let i = 0; i < text.length; i++) {
                setTimeout(() => {
                  current += text[i];
                  $ip.textContent = current;

                  if (i === text.length - 1 && callback) {
                    callback();
                  }
                }, 120 * i); // скорость
              }
            }

            function startTyping() {
              writeIp(ip, () => {
                setTimeout(startTyping, 4000);
              });
            }

            setTimeout(startTyping, 800);
          });
        </script>


        <div class="beauty-actions">
          <a class="beauty-btn" href="./howto/">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M6.51 18.87c.15.09.32.13.49.13s.36-.05.51-.14l10-6c.3-.18.49-.51.49-.86s-.18-.68-.49-.86l-10-6a.99.99 0 0 0-1.01-.01c-.31.18-.51.51-.51.87v12c0 .36.19.69.51.87Z">
              </path>
            </svg>
            Начать играть
          </a>
          <a class="beauty-btn secondary" href="./games/">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M19 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h11c2.76 0 5-2.24 5-5V4c0-1.1-.9-2-2-2m-7 16h-2v2H8v-2H6v-2h2v-2h2v2h2zm3 1c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m2-2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m1-5H6V5h12z">
              </path>
            </svg>
            Режимы
          </a>
          <a class="beauty-btn secondary" href="./forum/">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M12 2C6.49 2 2 6.49 2 12c0 2.12.68 4.19 1.93 5.9l-1.75 2.53c-.21.31-.24.7-.06 1.03.17.33.51.54.89.54h9c5.51 0 10-4.49 10-10S17.51 2 12 2M6 9h3v2H6zm7 6H6v-2h7zm5 0h-3v-2h3zm0-4h-7V9h7z">
              </path>
            </svg>
            Форум
          </a>
        </div>
      </section>
      <section class="beauty-grid">
        <a class="beauty-card" href="./howto/">
          <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" >
              <path d="M5 16c-2 1-2 5-2 5s3 0 5-2zM21 2h-3.69c-2.4 0-4.66.94-6.36 2.64L8.69 6.9a8.4 8.4 0 0 0-6.24 1.27c-.25.17-.41.44-.44.73s.08.59.29.81l12 12c.2.2.45.29.71.29s.51-.1.71-.29c1.9-1.9 1.6-5.08 1.38-6.38l2.28-2.28c1.7-1.7 2.64-3.96 2.64-6.36V3c0-.55-.45-1-1-1Zm-3.59 7.41c-.78.78-2.05.78-2.83 0s-.78-2.05 0-2.83 2.05-.78 2.83 0 .78 2.05 0 2.83"></path>
            </svg>
          <h3>Быстрый старт</h3>
          <p>Пошагово: версия, IP, добавление сервера и регистрация.</p>
        </a>
        <a class="beauty-card" href="./page/games/">
          <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" >
            <path d="M19 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h11c2.76 0 5-2.24 5-5V4c0-1.1-.9-2-2-2m-7 16h-2v2H8v-2H6v-2h2v-2h2v2h2zm3 1c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m2-2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m1-5H6V5h12z"></path>
          </svg>
          <h3>Режимы</h3>
          <p>Prison, SkyBlock, BedWars, SkyWars, Murder Mystery и другие.</p>
        </a>
        <a class="beauty-card" href="./forum/">
          <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
						<path d="M12 2C6.49 2 2 6.49 2 12c0 2.12.68 4.19 1.93 5.9l-1.75 2.53c-.21.31-.24.7-.06 1.03.17.33.51.54.89.54h9c5.51 0 10-4.49 10-10S17.51 2 12 2M6 9h3v2H6zm7 6H6v-2h7zm5 0h-3v-2h3zm0-4h-7V9h7z"></path>
					</svg>
          <h3>Форум</h3>
          <p>Новости, вопросы, баги, идеи, помощь и обсуждения игроков.</p>
        </a>
        <a class="beauty-card" href="./fill/">
          <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
						<path d="M21 8H7c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1m-1 8c-1.1 0-2 .9-2 2h-8c0-1.1-.9-2-2-2v-4c1.1 0 2-.9 2-2h8c0 1.1.9 2 2 2z"></path>
						<path d="M18 4H3c-.55 0-1 .45-1 1v11h2V6h14zm-4 8a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
					</svg>
          <h3>Леденцы</h3>
          <p>Валюта проекта, поддержка сервера и приятные бонусы.</p>
        </a>
      </section>
      <section class="lolli-card">
        <div class="two-col">
          <div>
            <h2>Что нового</h2>
            <p class="muted">Сайт приведён к единому стилю: аккуратные карточки, адаптивные блоки, улучшенные формы,
              копирование IP, обновлённый кабинет и раздел леденцов.</p>
            <div><a class="beauty-pill" href="./news/cosmetics/">😱 Косметика</a><a class="beauty-pill"
                href="./help/">Помощь</a><a class="beauty-pill" href="./page/rules/">Правила</a>
            </div>
          </div>
          <div class="notice"><b>Совет</b>
            <p class="muted">Если картинки или стили не обновились после замены файлов, нажмите Ctrl+F5.</p>
          </div>
        </div>
      </section>
    </div>
  </main>

</body>

</html>