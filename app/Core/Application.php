<?php

declare(strict_types=1);

namespace Orbit\Core;

final class Application
{
    public function run(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        match ($path) {
            '/', '/home' => $this->home(),
            '/health' => $this->health(),
            default => $this->notFound(),
        };
    }

    private function home(): void
    {
        http_response_code(200);
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>ORBIT App</title></head><body><main style="font-family:system-ui;max-width:760px;margin:10vh auto;padding:24px"><h1>ORBIT App</h1><p>Pure PHP 8.4 member platform foundation is running.</p><p><a href="/health">Health check</a></p></main></body></html>';
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
