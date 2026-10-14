<?php

class Accordion {
    private string $title;
    private string $description;
    private bool $open;

    public function __construct(
        string $title,
        string $description,
        bool $open = false
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->open = $open;
    }

    public function render(): string {
        $openAttr = $this->open ? ' open' : '';

        return sprintf(
            '<details class='. "'accordion'" .' %s>
                <summary>%s</summary>
                <p>%s</p>
            </details>',
            $openAttr,
            htmlspecialchars($this->title),
            htmlspecialchars($this->description)
        );
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