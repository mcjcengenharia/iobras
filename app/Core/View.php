<?php

class View
{
    private static ?string $layout = null;

    public static function extends(string $layout): void
    {
        self::$layout = $layout;
    }

    public static function render(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new Exception('View não encontrada: ' . $view);
        }

        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        if (self::$layout) {
            $layoutPath = __DIR__ . '/../Views/' . self::$layout . '.php';
            if (!file_exists($layoutPath)) {
                throw new Exception('Layout não encontrado: ' . self::$layout);
            }

            $__content = $content;
            $currentLayout = self::$layout;
            self::$layout = null;

            include $layoutPath;
            return;
        }

        self::$layout = null;
        echo $content;
    }
}
