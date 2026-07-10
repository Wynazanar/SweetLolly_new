<?php
// === SVG ИКОНКИ ДЛЯ КАТЕГОРИЙ ===
function getCategorySVG($categoryName)
{
  $svgs = [
    'Общие правила'       => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-13c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1s1-.45 1-1V8c0-.55-.45-1-1-1zm0 8c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1z"/></svg>',

    'Чат'                 => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>',

    'Аккаунт'             => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>',

    'Игровой процесс'     => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M21 6h-2v9H5V6H3v9c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6zM11 3h2v2h-2z"/></svg>',

    'Экономика'           => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.39L12 11.5l-.31-.89C10.9 9.3 9.9 9 9 9c-1.1 0-2 .9-2 2s.9 2 2 2c.9 0 1.9-.3 2.69-.89z"/></svg>',

    'Постройки'           => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>',

    'Режимы'              => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>',

    'Технические правила' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.93l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.2-1.17.53-1.69.93l-2.49-1c-.22-.08-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.93l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.2 1.17-.53 1.69-.93l2.49 1c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"/></svg>',

  ];

  foreach ($svgs as $key => $svg) {
    if (stripos($categoryName, $key) !== false) {
      return $svg;
    }
  }

  return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-13c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1s1-.45 1-1V8c0-.55-.45-1-1-1zm0 8c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1z"/></svg>';
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Правила | SweetLolly</title>
  <link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/rules.css">
</head>

<body class="">
  <main style="Margin-top: 30px;">
    <div class="container rules-modern">
      <section class="beauty-hero"><span class="beauty-eyebrow"><i class="fa fa-gavel"></i> Правила проекта</span>
        <h1>Правила SweetLolly</h1>
        <p>Обновлённый и более удобный раздел правил. Здесь собраны основные требования к чату, аккаунтам,
          игровому процессу, экономике, постройкам, режимам и обращениям.</p>
        <div class="rules-search"><input id="rulesSearch"
            placeholder="Быстрый поиск по правилам: читы, спам, баги, обмены..."></div>
        <div class="beauty-actions">
          <a class="beauty-btn" href="/lollipopmc/forum/">
            <i class="fa fa-comments"></i>
            Задать вопрос на форуме
          </a>
          <a class="beauty-btn secondary" href="/lollipopmc/help/">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 22c5.51 0 10-4.49 10-10S17.51 2 12 2 2 6.49 2 12s4.49 10 10 10M11 7h2v2h-2zm0 4h2v6h-2z"></path>
            </svg>
            Центр помощи
          </a>
        </div>
      </section>
      <div class="rules-warning"><b>Важно:</b> незнание правил не освобождает от ответственности. Если ситуация не
        описана буквально, администрация принимает решение по смыслу правил и ради безопасности проекта.</div>
      <div class="rules-toc">
        <?php foreach ($ruleCategories as $ruleCategory): ?>
          <a href="#<?= htmlspecialchars($ruleCategory['name']) ?>">
            <?= getCategorySVG($ruleCategory['name']) ?>
            <?= htmlspecialchars($ruleCategory['name']) ?>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="rules-layout">
        <aside class="rules-sidebar">
          <h3>Разделы</h3>
          <?php foreach ($ruleCategories as $ruleCategory): ?>
            <a href="#<?= htmlspecialchars($ruleCategory['name']) ?>">
              <?= getCategorySVG($ruleCategory['name']) ?>
              <?= htmlspecialchars($ruleCategory['name']) ?>
            </a>
          <?php endforeach; ?>
        </aside>
        <div>
          <?php foreach ($ruleCategories as $keyC => $ruleCategory): ?>
            <section class="rules-section" id="<?= $ruleCategory['name'] ?>">
              <h2><i class="fa fa-shield-alt"></i><?= $ruleCategory['name'] ?></h2>
              <div class="rules-list-modern">
                <?php
                $ruleNumber = 1;
                foreach ($rules as $rule):
                  if ($rule['category_id'] == $ruleCategory['id']):
                ?>
                    <div class="rule-item" data-rule>
                      <div class="rule-num"><?= $keyC + 1 . '.' . $ruleNumber ?></div>
                      <div>
                        <b><?= htmlspecialchars($rule['title']) ?></b>
                        <p><?= htmlspecialchars($rule['rule']) ?></p>

                        <span class="punishment">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" transform="scale(-1,1)">
                            <path d="M2 20h13v2H2zM18.71 8.71a.996.996 0 0 0 0-1.41l-5-5a.996.996 0 0 0-1.41 0l-1.29 1.29L17.42 10zm-10.42 9c.2.2.45.29.71.29s.51-.1.71-.29L11 16.42l-6.41-6.41L3.3 11.3a.996.996 0 0 0 0 1.41l5 5Zm5.21-3.8 6.79 6.8 1.42-1.42-6.8-6.79L16 11.41 9.59 5 6 8.59 12.41 15z"></path>
                          </svg>
                          <?= htmlspecialchars(json_decode($rule['punishments'])[0] ?? '—') ?>
                        </span>
                      </div>
                    </div>
                <?php
                    $ruleNumber++;
                  endif;
                endforeach;
                ?>
              </div>
            </section>

          <?php endforeach; ?>

          <section class="rules-section">
            <h2><i class="fa fa-clock"></i> Наказания и сроки</h2>
            <p class="muted">Срок наказания зависит от тяжести нарушения, истории аккаунта и поведения
              игрока после нарушения. Администрация может заменить наказание предупреждением, мутом,
              киком, временным баном, перманентным баном, откатом действий или удалением запрещённых
              объектов.</p>
            <div class="rule-note"><b>Если не согласны с наказанием:</b> создайте спокойное обращение на
              форуме, приложите доказательства и опишите ситуацию по пунктам.</div>
          </section>
        </div>
      </div>
    </div>
  </main>

</body>
<script>
  var rs = document.getElementById('rulesSearch');

  if (rs) {
    rs.addEventListener('input', function() {
      var q = rs.value.toLowerCase().trim();
      document.querySelectorAll('[data-rule]')
        .forEach(function(el) {
          el.style.display = !q ||
            el.textContent.toLowerCase().indexOf(q) !==
            -1 ? '' : 'none';
        });
    });
  }
</script>

</html>