<?php
namespace Core;

class View
{
    public function render(Page $page)
    {
        return $this->renderLayout($page, $this->renderView($page));
    }

    private function renderLayout(Page $page, $content)
    {
        $layoutPath = dirname(__DIR__) . "/Views/layouts/{$page->layout}.php";

        if (!file_exists($layoutPath)) {
            throw new \RuntimeException("Layout not found: {$layoutPath}");
        }

        ob_start();
        $title = $page->title;
        include $layoutPath;
        return ob_get_clean();
    }

    private function renderView(Page $page)
    {
        if (!$page->view) {
            return '';
        }

        $viewPath = dirname(__DIR__) . "/Views/{$page->view}.php";

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$viewPath}");
        }

        ob_start();
        $data = $page->data;
        extract($data);
        include $viewPath;
        return ob_get_clean();
    }
}