<?php
function getCategorySVG($categoryName)
{
  $svgs = [
    'Общие правила' =>
      '<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" >
        <path d="M20.42 6.11 13 2.39v19.24c8.87-4.42 7.97-14.62 7.96-14.73a.98.98 0 0 0-.55-.79ZM3.04 6.9C3.03 7 2.13 17.21 11 21.63V2.38L3.58 6.1c-.31.15-.51.45-.55.79Z"></path>
      </svg>',

    'Чат' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>',

    'Аккаунт' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>',

    'Игровой процесс' =>
      '<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
        <path d="M19 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h11c2.76 0 5-2.24 5-5V4c0-1.1-.9-2-2-2m-7 16h-2v2H8v-2H6v-2h2v-2h2v2h2zm3 1c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m2-2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m1-5H6V5h12z"></path>
      </svg>',

    'Экономика и обмены' =>
      '<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" >
        <path d="M21 8H7c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1m-1 8c-1.1 0-2 .9-2 2h-8c0-1.1-.9-2-2-2v-4c1.1 0 2-.9 2-2h8c0 1.1.9 2 2 2z"></path><path d="M18 4H3c-.55 0-1 .45-1 1v11h2V6h14zm-4 8a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
      </svg>',

    'Постройки и территория' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>',

    'Мини-игры и режимы' =>
      '<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"fill="currentColor" viewBox="0 0 24 24" >
        <path d="M21 4h-3V3c0-.55-.45-1-1-1H7c-.55 0-1 .45-1 1v1H3c-.55 0-1 .45-1 1v3c0 4.29 1.79 6.88 4.81 6.99A6 6 0 0 0 11 17.91V20H8v2h8v-2h-3v-2.09a5.98 5.98 0 0 0 4.19-2.92C20.2 14.88 22 12.29 22 8V5c0-.55-.45-1-1-1M4 8V6h2v6c0 .28.03.56.06.83C4.22 12.12 4 9.31 4 8m16 0c0 1.31-.22 4.12-2.06 4.83.04-.27.06-.55.06-.83V6h2z"></path>
      </svg>',

    'Жалобы и обжалования' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.93l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.2-1.17.53-1.69.93l-2.49-1c-.22-.08-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.93l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.2 1.17-.53 1.69-.93l2.49 1c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"/></svg>',

  ];

  foreach ($svgs as $key => $svg) {
    if (stripos($categoryName, $key) !== false) {
      return $svg;
    }
  }

  return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-13c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1s1-.45 1-1V8c0-.55-.45-1-1-1zm0 8c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1z"/></svg>';
}
?>

<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/rules.css">
<main>
  <div class="container">
    <p class="main-page">SweetLolly / #Правила</p>
    <h1 class="main-title">Играть проще, когда есть правила</h1>
    <p class="main-subtitle">Договоримся о главном: уважение, честная игра и безопасность каждого игрока.</p>
    
    <div class="rules-warning">
      <b>Важно:</b>
      незнание правил не освобождает от ответственности. Если ситуация не
      описана буквально, администрация принимает решение по смыслу правил и ради безопасности проекта.
    </div>

    <div class="rules-layout">
      <aside class="rules-sidebar">
        <div class="rules-sections">
          <h3 class="rules-sb-title">Разделы</h3>
          <?php foreach ($ruleCategories as $ruleCategory): ?>
            <a href="#<?= htmlspecialchars($ruleCategory['name']) ?>" class="<?= $ruleCategory['name'] == 'Общие правила'? 'active' : '' ?>">
              <?= getCategorySVG($ruleCategory['name']) ?>
              <?= htmlspecialchars($ruleCategory['name']) ?>
            </a>
          <?php endforeach; ?>
        </div>
        <div class="aside-help">
          <p class="aside-help-title">Нужна помощь?</p>
          <p class="aside-help-descr">Не уверен, как трактовать правило? Лучше уточнить до начала игры.</p>
          <a class="aside-help-link" href="#">Задать вопрос</a>
        </div>
      </aside>
        
        <div>
          <?php foreach ($ruleCategories as $keyC => $ruleCategory): ?>
            <section class="rules-section" id="<?= $ruleCategory['name'] ?>">
              <h2>
                <?= $ruleCategory['name'] ?>
              </h2>
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
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            viewBox="0 0 24 24" transform="scale(-1,1)">
                            <path
                              d="M2 20h13v2H2zM18.71 8.71a.996.996 0 0 0 0-1.41l-5-5a.996.996 0 0 0-1.41 0l-1.29 1.29L17.42 10zm-10.42 9c.2.2.45.29.71.29s.51-.1.71-.29L11 16.42l-6.41-6.41L3.3 11.3a.996.996 0 0 0 0 1.41l5 5Zm5.21-3.8 6.79 6.8 1.42-1.42-6.8-6.79L16 11.41 9.59 5 6 8.59 12.41 15z">
                            </path>
                          </svg>

                          <?php
                          $punishments = json_decode($rule['punishments'], true);

                          if (is_array($punishments) && !empty($punishments)):
                            $punishList = array_map(function ($p) {
                              return htmlspecialchars(trim($p));
                            }, $punishments);

                            echo implode(' / ', $punishList);
                          else:
                            echo '<i class="fa fa-gavel">—</i>';
                          endif;
                          ?>
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
            <h2>
              <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" >
                <path d="M12 2C6.58 2 2 6.58 2 12s4.58 10 10 10 10-4.58 10-10S17.42 2 12 2m5 11h-6V7h2v4h4z"></path>
              </svg>
              Наказания и сроки
            </h2>
            <p class="muted">Срок наказания зависит от тяжести нарушения, истории аккаунта и поведения
              игрока после нарушения. Администрация может заменить наказание предупреждением, мутом,
              киком, временным баном, перманентным баном, откатом действий или удалением запрещённых
              объектов.</p>
            <div class="rule-note">
              <b>Если не согласны с наказанием:</b> создайте спокойное обращение на
              форуме, приложите доказательства и опишите ситуацию по пунктам.</div>
          </section>
        </div>

    </div>

    <div style="margin-top: 24px;; padding: 22px; gap:16px; display: flex; align-items: center; background: #F0E8FF; border-radius: 18px;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.6667 23.3333C18.095 23.3333 23.3333 18.095 23.3333 11.6667C23.3333 5.23833 18.095 0 11.6667 0C5.23833 0 0 5.23833 0 11.6667C0 18.095 5.23833 23.3333 11.6667 23.3333ZM10.5 5.83333H12.8333V8.16667H10.5V5.83333ZM10.5 10.5H12.8333V17.5H10.5V10.5Z" fill="#8B57FF"/>
          </svg>
          <div>
            <p style="margin-bottom: 6px; font-size: 15px; font-weight: 700;">Заметил нарушение?</p>
            <p style="font-size: 14px; color:var(--footer-gray-color);">Не вступай в конфликт. Сохрани доказательства и передай их в поддержку через раздел «Помощь».</p>
          </div>
        </div>
  </div>
</main>