<?php

class Router
{
    private array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    public function get(string $path, $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = $this->normalize($uri);

        $handler = $this->routes[$method][$uri] ?? null;

        if (!$handler) {
            http_response_code(404);
            echo '404 - Página não encontrada';
            return;
        }

        if (is_array($handler)) {
            [$class, $methodName] = $handler;
            $controller = new $class();
            $controller->$methodName();
            return;
        }

        call_user_func($handler);
    }
}

