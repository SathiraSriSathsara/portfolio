<?php
declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = [], string $layout = 'public'): string
    {
        extract($data, EXTR_SKIP); ob_start(); require BASE_PATH . '/app/Views/' . $view . '.php'; $content = ob_get_clean();
        ob_start(); require BASE_PATH . '/app/Views/layouts/' . $layout . '.php'; return (string) ob_get_clean();
    }
}
