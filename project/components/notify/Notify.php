<?php
use Project\Models\Notification;
use Project\Services\BridgeApi;

class Notify
{
    public function render(): string
    {
        $nick = $_SESSION['nickname'] ?? '';
        $csrf = htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8');

        $items = [];
        $unread = 0;
        $pending = [];

        if ($nick !== '') {
            $model = new Notification();
            $items = $model->recentFor($nick, 30);
            $unread = $model->unreadCount($nick);

            $bridge = new BridgeApi();
            foreach ($bridge->getFriendRequests($nick) ?? [] as $r) {
                $n = $r['player_name'] ?? '';
                if ($n !== '') $pending[strtolower($n)] = true;
            }
        }

        ob_start();
        ?>
        <div class="theme-toggle notify" id="notify-root">
            <button type="button" class="notify-bell" id="notify-bell">
                <svg  xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 24 24" >
                    <path d="M19 12.59V10c0-3.22-2.18-5.93-5.14-6.74C13.57 2.52 12.85 2 12 2s-1.56.52-1.86 1.26C7.18 4.08 5 6.79 5 10v2.59L3.29 14.3a1 1 0 0 0-.29.71v2c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-2c0-.27-.11-.52-.29-.71zM14.82 20H9.18c.41 1.17 1.51 2 2.82 2s2.41-.83 2.82-2"></path>
                </svg>
                <span class="notify-badge" id="notify-badge" <?= $unread ? '' : 'hidden' ?>>
                    <?= $unread > 99 ? '99+' : (int)$unread ?>
                </span>
            </button>

            <div class="notify-dropdown" id="notify-dropdown" hidden>
                <div class="notify-dropdown-title">Уведомления</div>
                <div class="notify-list" id="notify-list">
                    <?php if (!$items): ?>
                        <div class="notify-empty">Нет уведомлений</div>
                    <?php else: ?>
                        <?php foreach ($items as $it):
                            $msg = $it['payload']['message'] ?? $it['event_type'];
                            $from = $it['payload']['from'] ?? '';
                            $canAct = ($it['event_type'] === 'friend_request')
                                && $from !== ''
                                && !empty($pending[strtolower($from)]);
                            ?>
                            <div class="notify-item<?= empty($it['is_read']) ? ' unread' : '' ?>">
                                <div class="notify-item-msg"><?= htmlspecialchars($msg) ?></div>
                                <?php if ($canAct): ?>
                                    <div class="notify-item-actions">
                                        <form method="post" action="/SweetLolly_new/friends/accept/" style="display:inline">
                                            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                                            <input type="hidden" name="from" value="<?= htmlspecialchars($from) ?>">
                                            <button type="submit" class="notify-accept">Принять</button>
                                        </form>
                                        <form method="post" action="/SweetLolly_new/friends/reject/" style="display:inline">
                                            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                                            <input type="hidden" name="from" value="<?= htmlspecialchars($from) ?>">
                                            <button type="submit" class="notify-reject">Отклонить</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <script defer>
            (function () {
                const root = document.getElementById('notify-root');
                if (!root) return;
                const bell = document.getElementById('notify-bell');
                const dropdown = document.getElementById('notify-dropdown');
                const badge = document.getElementById('notify-badge');

                bell.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const open = dropdown.hidden;
                    dropdown.hidden = !open;
                    if (open) {
                    fetch('/SweetLolly_new/api/notifications/opened', {
                        method: 'POST', credentials: 'same-origin'
                    }).then(r => r.json()).then(d => {
                        if (d.unread > 0) {
                        badge.hidden = false;
                        badge.textContent = d.unread > 99 ? '99+' : d.unread;
                        } else badge.hidden = true;
                    }).catch(() => {});
                    }
                });

                document.addEventListener('click', (e) => {
                    if (!root.contains(e.target)) dropdown.hidden = true;
                });

                // автообновление бейджа (без перерисовки всего списка в JS)
                setInterval(() => {
                    fetch('/SweetLolly_new/api/notifications', { credentials: 'same-origin' })
                    .then(r => r.json())
                    .then(d => {
                        const n = d.unread || 0;
                        if (n > 0) {
                        badge.hidden = false;
                        badge.textContent = n > 99 ? '99+' : String(n);
                        } else badge.hidden = true;
                    }).catch(() => {});
                }, 10000);
                })();
        </script>
        <?php
        return ob_get_clean();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public static function make(...$args): self
    {
        return new self(...$args);
    }
}