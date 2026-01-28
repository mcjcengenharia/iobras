<?php

class RoleMiddleware
{
    public static function allow(array $roles): void
    {
        if (!class_exists('Auth') || !Auth::check()) {
            header('Location: /iobras/public/login');
            exit;
        }

        if (!Auth::hasRole($roles)) {
            http_response_code(403);
            exit('403 - Sem permissão');
        }
    }
}
