<?php

declare(strict_types=1);

namespace Orbit\Core;

final class View
{
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';
        $layoutPath = dirname(__DIR__) . '/Views/layouts/' . $layout . '.php';

        if (!is_file($viewPath)) {
            http_response_code(500);
            echo 'View not found.';
            return;
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if (is_file($layoutPath)) {
            require $layoutPath;
            return;
        }

        echo $content;
    }
}
