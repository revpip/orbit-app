<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefix = 'Orbit\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = __DIR__ . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

require __DIR__ . '/helpers.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name(env('SESSION_NAME', 'orbit_session'));
    session_start();
}
