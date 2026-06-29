<?php

declare(strict_types=1);

namespace Orbit\Core;

use Orbit\Controllers\AuthController;
use Orbit\Controllers\HomeController;
use Orbit\Controllers\ModerationController;
use Orbit\Controllers\OnboardingController;
use Orbit\Controllers\PsychologyController;
use Orbit\Controllers\SafetyController;
use Orbit\Controllers\SuggestionController;

final class Application
{
    public function run(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        match ([$method, $path]) {
            ['GET', '/'], ['GET', '/home'] => (new HomeController())->index(),
            ['GET', '/dashboard'] => (new HomeController())->memberHome(),
            ['GET', '/onboarding/profile'] => (new OnboardingController())->profile(),
            ['POST', '/onboarding/profile'] => (new OnboardingController())->saveProfile(),
            ['GET', '/onboarding/intents'] => (new OnboardingController())->intents(),
            ['POST', '/onboarding/intents'] => (new OnboardingController())->saveIntents(),
            ['GET', '/onboarding/psychology'] => (new PsychologyController())->edit(),
            ['POST', '/onboarding/psychology'] => (new PsychologyController())->save(),
            ['GET', '/matches'] => (new SuggestionController())->index(),
            ['GET', '/safety/report'] => (new SafetyController())->reportForm(),
            ['POST', '/safety/report'] => (new SafetyController())->submitReport(),
            ['POST', '/safety/block'] => (new SafetyController())->block(),
            ['GET', '/safety/thanks'] => (new SafetyController())->thanks(),
            ['GET', '/moderation'] => (new ModerationController())->queue(),
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
