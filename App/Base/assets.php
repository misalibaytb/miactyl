<?php
//Made by VoltPHP. Do not touch!

class publicRender
{
    public function render($path)
    {
        if (file_exists($path) && is_file($path)) {
            require_once $path;
            return;
        }
        if (file_exists($path . '.html') && is_file($path . '.html')) {
            require_once $path . '.html';
            return;
        }
        if (file_exists($path . 'index.html') && is_file($path . 'index.html')) {
            require_once $path . 'index.html';
            return;
        }
        throw new Exception("File not found.");
    }
    public function fallback($path)
    {
        try {
            (new publicRender())->render($path);
        } catch (Exception $e) {
            try {
                (new publicRender())->render('');
            } catch (Exception $e) {
                require_once 'errors/404.php';
            }
        }
    }
}
