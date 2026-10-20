<h1>Друзья</h1>

<?php if (!empty($_SESSION['flash'])): ?>
  <p><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></p>
<?php endif; ?>

<h2>Входящие заявки</h2>
<?php foreach ($requests as $r): ?>
  <div>
    <?= htmlspecialchars($r['player_name'] ?? '') ?>
    <form method="post" action="/SweetLolly_new/friends/accept/" style="display:inline">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
      <input type="hidden" name="from" value="<?= htmlspecialchars($r['player_name'] ?? '') ?>">
      <button type="submit">Принять</button>
    </form>
    <form method="post" action="/SweetLolly_new/friends/reject/" style="display:inline">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
      <input type="hidden" name="from" value="<?= htmlspecialchars($r['player_name'] ?? '') ?>">
      <button type="submit">Отклонить</button>
    </form>
  </div>
<?php endforeach; ?>

<h2>Мои друзья</h2>
<ul>
<?php foreach ($friends as $f): ?>
  <?php $fname = $f['player_name'] ?? ''; ?>
  <li>
    <?= htmlspecialchars($fname) ?>
    <form method="post" action="/SweetLolly_new/friends/remove/" style="display:inline">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
      <input type="hidden" name="to" value="<?= htmlspecialchars($fname) ?>">
      <button type="submit" onclick="return confirm('Удалить <?= htmlspecialchars($fname) ?> из друзей?')">
        Удалить
      </button>
    </form>
  </li>
<?php endforeach; ?>
</ul>

<h2>Добавить в друзья</h2>
<form method="post" action="/SweetLolly_new/friends/request/">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
  <input type="text" name="to" placeholder="Ник игрока" required>
  <button type="submit">Отправить заявку</button>
</form>