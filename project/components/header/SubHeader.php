<?php

class SubHeader
{
    public function render(): string
    {
        ob_start();
        ?>
        <div>
            <div class="container">
                <a href="#">asdas</a>
                <a href="#">asdas</a>
                <a href="#">asdas</a>
                <a href="#">asdas</a>
            </div>
        </div>
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