<h1>Друзья</h1>

<?php if (!empty($_SESSION['flash'])): ?>
  <p><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></p>
<?php endif; ?>

<h2>Уведомления</h2>
<?php foreach ($notifications as $n): ?>
  <div>
    <strong><?= htmlspecialchars($n['event_type']) ?></strong>
    <?= htmlspecialchars($n['payload']['message'] ?? '') ?>
    <?php if (($n['event_type'] ?? '') === 'friend_request'): ?>
      <?php $from = $n['payload']['from'] ?? ''; ?>
      <form method="post" action="/SweetLolly_new/friends/accept/" style="display:inline">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="from" value="<?= htmlspecialchars($from) ?>">
        <button type="submit">Принять</button>
      </form>
      <form method="post" action="/SweetLolly_new/friends/reject/" style="display:inline">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="from" value="<?= htmlspecialchars($from) ?>">
        <button type="submit">Отклонить</button>
      </form>
    <?php endif; ?>
  </div>
<?php endforeach; ?>

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
  <li><?= htmlspecialchars($f['player_name'] ?? '') ?></li>
<?php endforeach; ?>
</ul>

<h2>Добавить в друзья</h2>
<form method="post" action="/SweetLolly_new/friends/request/">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
  <input type="text" name="to" placeholder="Ник игрока" required>
  <button type="submit">Отправить заявку</button>
</form>