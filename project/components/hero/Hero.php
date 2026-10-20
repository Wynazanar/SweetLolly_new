<?php

class Hero
{
    public function render(): string
    {
        ob_start();
        ?>
        <section class="hero-nav">
            <div class="container">
                <div class="hero-row">
                    <a href="SweetLolly_new/" class="site-logo" aria-label="SweetLolly">
                        <img src="/SweetLolly_new/project/webroot/resources/logo.png" style="max-width: 80%;">
                    </a>
                    <div class="promo">
                        <div>Присоединись к игрокам<br>уже сейчас!</div>
                        <a href="./howto/" class="btn btn-lollypop">Начать играть :)</a>
                        <br>
                        <div class="clipboard">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <rect width="14" height="14" x="8" y="2" rx="2" ry="2"></rect>
                                <path
                                    d="M8.5 18A2.5 2.5 0 0 1 6 15.5V8H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2v-2z">
                                </path>
                            </svg>
                            <p>IP:</p>
                            <span class="copy_id">play.swetlolly.net</span>
                        </div>
                    </div>
                </div>
                <nav class="main-menu">
                    <a href="/SweetLolly_new/forum/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.49 2 2 6.49 2 12c0 2.12.68 4.19 1.93 5.9l-1.75 2.53c-.21.31-.24.7-.06 1.03.17.33.51.54.89.54h9c5.51 0 10-4.49 10-10S17.51 2 12 2M6 9h3v2H6zm7 6H6v-2h7zm5 0h-3v-2h3zm0-4h-7V9h7z">
                            </path>
                        </svg>
                        Форум
                    </a>
                    <a href="/SweetLolly_new/rules/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24" transform="scale(-1,1) ">
                            <path
                                d="M2 20h13v2H2zM18.71 8.71a.996.996 0 0 0 0-1.41l-5-5a.996.996 0 0 0-1.41 0l-1.29 1.29L17.42 10zm-10.42 9c.2.2.45.29.71.29s.51-.1.71-.29L11 16.42l-6.41-6.41L3.3 11.3a.996.996 0 0 0 0 1.41l5 5Zm5.21-3.8 6.79 6.8 1.42-1.42-6.8-6.79L16 11.41 9.59 5 6 8.59 12.41 15z">
                            </path>
                        </svg>
                        Правила
                    </a>
                    <a href="/SweetLolly_new/games/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M19 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h11c2.76 0 5-2.24 5-5V4c0-1.1-.9-2-2-2m-7 16h-2v2H8v-2H6v-2h2v-2h2v2h2zm3 1c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m2-2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m1-5H6V5h12z">
                            </path>
                        </svg>
                        Режимы
                    </a>
                    <a href="/SweetLolly_new/candies/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M21 8H7c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1m-1 8c-1.1 0-2 .9-2 2h-8c0-1.1-.9-2-2-2v-4c1.1 0 2-.9 2-2h8c0 1.1.9 2 2 2z">
                            </path>
                            <path d="M18 4H3c-.55 0-1 .45-1 1v11h2V6h14zm-4 8a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
                        </svg>
                        Леденцы
                    </a>
                    <a href="/SweetLolly_new/help/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 22c5.51 0 10-4.49 10-10S17.51 2 12 2 2 6.49 2 12s4.49 10 10 10M11 7h2v2h-2zm0 4h2v6h-2z">
                            </path>
                        </svg>
                        Помощь
                    </a>
                </nav>
            </div>
        </section>
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