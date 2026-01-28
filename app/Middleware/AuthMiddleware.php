<?php

class AuthMiddleware
{
    public static function requireLogin(): void
    {
        if (!class_exists('Auth')) {
            http_response_code(500);
            exit('Auth não carregado');
        }

        if (!Auth::check()) {
            header('Location: /iobras/public/login');
            exit;
        }
    }
}
