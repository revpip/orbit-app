<?php

declare(strict_types=1);

namespace Orbit\Core;

use Orbit\Controllers\AuthController;
use Orbit\Controllers\HomeController;

final class Application
{
    public function run(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        match ([$method, $path]) {
            ['GET', '/'], ['GET', '/home'] => (new HomeController())->index(),
            ['GET', '/dashboard'] => (new HomeController())->memberHome(),
            ['GET', '/register'] => (new AuthController())->showRegister(),
            ['POST', '/register'] => (new AuthController())->register(),
            ['GET', '/login'] => (new AuthController())->showLogin(),
            ['POST', '/login'] => (new AuthController())->login(),
            ['POST', '/logout'] => (new AuthController())->logout(),
            ['GET', '/health'] => $this->health(),
            default => $this->notFound(),
        };
    }

    private function health(): void
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'ok',
            'app' => env('APP_NAME', 'ORBIT'),
            'php' => PHP_VERSION,
        ], JSON_PRETTY_PRINT);
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '404 Not Found';
    }
}
