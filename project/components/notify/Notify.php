<?php

use Project\Models\Notification;

class Notify
{
    public function render(): string
    {
        $nick = $_SESSION['nickname'] ?? '';
        $csrf = htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8');

        // Только лёгкий COUNT — без Bridge и без списка из 30 строк
        $unread = 0;
        if ($nick !== '') {
            $unread = (new Notification())->unreadCount($nick);
        }

        ob_start();
        ?>
        <div class="notify" id="notify-root" data-csrf="<?= $csrf ?>">
            <button type="button" class="theme-toggle notify-bell" id="notify-bell" aria-label="Уведомления">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 12.59V10c0-3.22-2.18-5.93-5.14-6.74C13.57 2.52 12.85 2 12 2s-1.56.52-1.86 1.26C7.18 4.08 5 6.79 5 10v2.59L3.29 14.3a1 1 0 0 0-.29.71v2c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-2c0-.27-.11-.52-.29-.71zM14.82 20H9.18c.41 1.17 1.51 2 2.82 2s2.41-.83 2.82-2"></path>
                </svg>
                <span class="notify-badge" id="notify-badge" <?= $unread ? '' : 'hidden' ?>>
                    <?= $unread > 99 ? '99+' : (int)$unread ?>
                </span>
            </button>

            <div class="notify-dropdown" id="notify-dropdown" hidden>
                <div class="notify-dropdown-title">Уведомления</div>
                <div class="notify-list" id="notify-list">
                    <div class="notify-empty">Загрузка…</div>
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
            const list = document.getElementById('notify-list');
            const csrf = root.dataset.csrf || '';
            let loaded = false;

            function setBadge(n) {
                n = n || 0;
                if (n > 0) {
                    badge.hidden = false;
                    badge.textContent = n > 99 ? '99+' : String(n);
                } else {
                    badge.hidden = true;
                }
            }

            function esc(s) {
                const d = document.createElement('div');
                d.textContent = s == null ? '' : String(s);
                return d.innerHTML;
            }

            function renderItems(items) {
                if (!items || !items.length) {
                    list.innerHTML = '<div class="notify-empty">Нет уведомлений</div>';
                    return;
                }
                list.innerHTML = items.map(function (it) {
                    const msg = (it.payload && it.payload.message) || it.event_type || '';
                    const from = (it.payload && it.payload.from) || '';
                    const unreadCls = it.is_read ? '' : ' unread';
                    let actions = '';
                    if (it.can_act && from) {
                        actions =
                            '<div class="notify-item-actions">' +
                            '<form method="post" action="/SweetLolly_new/friends/accept/" style="display:inline">' +
                            '<input type="hidden" name="csrf_token" value="' + esc(csrf) + '">' +
                            '<input type="hidden" name="from" value="' + esc(from) + '">' +
                            '<button type="submit" class="notify-accept">Принять</button></form>' +
                            '<form method="post" action="/SweetLolly_new/friends/reject/" style="display:inline">' +
                            '<input type="hidden" name="csrf_token" value="' + esc(csrf) + '">' +
                            '<input type="hidden" name="from" value="' + esc(from) + '">' +
                            '<button type="submit" class="notify-reject">Отклонить</button></form>' +
                            '</div>';
                    }
                    return '<div class="notify-item' + unreadCls + '">' +
                        '<div class="notify-item-msg">' + esc(msg) + '</div>' + actions + '</div>';
                }).join('');
            }

            function loadList() {
                list.innerHTML = '<div class="notify-empty">Загрузка…</div>';
                fetch('/SweetLolly_new/api/notifications?full=1', { credentials: 'same-origin' })
                    .then(function (r) { return r.json(); })
                    .then(function (d) {
                        setBadge(d.unread || 0);
                        renderItems(d.items || []);
                        loaded = true;
                    })
                    .catch(function () {
                        list.innerHTML = '<div class="notify-empty">Не удалось загрузить</div>';
                    });
            }

            function pollBadge() {
                if (document.visibilityState !== 'visible') return;
                fetch('/SweetLolly_new/api/notifications', { credentials: 'same-origin' })
                    .then(function (r) { return r.json(); })
                    .then(function (d) { setBadge(d.unread || 0); })
                    .catch(function () {});
            }

            bell.addEventListener('click', function (e) {
                e.stopPropagation();
                const open = dropdown.hidden;
                dropdown.hidden = !open;
                if (open) {
                    loadList();
                    fetch('/SweetLolly_new/api/notifications/opened', {
                        method: 'POST',
                        credentials: 'same-origin'
                    })
                        .then(function (r) { return r.json(); })
                        .then(function (d) { setBadge(d.unread || 0); })
                        .catch(function () {});
                }
            });

            document.addEventListener('click', function (e) {
                if (!root.contains(e.target)) dropdown.hidden = true;
            });

            setInterval(pollBadge, 30000);
            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') pollBadge();
            });
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