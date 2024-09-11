<?php

use App\Base\JsParser;
use App\Http\Controllers\Auth\AuthController;


header('Content-Type: application/json');
Router::get('/api/user', AuthController::class, 'index')->name('auth.getUser');
Router::post('/api/login', AuthController::class, 'login');
Router::get('/api/login', AuthController::class, 'generateOauth2Url');
Router::middleware(['auth.getUser'])->group(function ($user) {
    Router::get('/api/logout', AuthController::class, 'logout');
});
Router::fallback('/', function ($path) {
    header('Content-Type: text/html');
    try {
        (new publicRender())->render($path);
    } catch (Exception $e) {
        try {
            if (!file_exists('../resources/views/layout.blade.php')) {
                throw new Exception("Error Processing Request", 1);
            }
            require_once '../resources/views/layout.blade.php';
        } catch (Exception $e) {
            require_once '../resources/views/errors/404.php';
        }
    }
});
