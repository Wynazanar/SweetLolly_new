<?php
namespace Core;

class View
{
    public function render(Page $page) {
        return $this->renderLayout($page, $this->renderView($page));
    }
    
    private function renderLayout(Page $page, $content) {
        global $projectRoot;
        
        $layoutPath = $projectRoot . "/project/layouts/{$page->layout}.php";
        
        if (file_exists($layoutPath)) {
            ob_start();
                $title = $page->title;
                include $layoutPath;
            return ob_get_clean();
        } else {
            echo "Layout file not found at path $layoutPath"; die();
        }
    }
    
    private function renderView(Page $page) {
        global $projectRoot;
        
        if ($page->view) {
            $viewPath = $projectRoot . "/project/views/{$page->view}.php";
            
            if (file_exists($viewPath)) {
                ob_start();
                    $data = $page->data;
                    extract($data);
                    include $viewPath;
                return ob_get_clean();
            } else {
                echo "View file not found at path $viewPath"; die();
            }
        }
    }
}