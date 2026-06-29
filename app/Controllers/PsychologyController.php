<?php

declare(strict_types=1);

namespace Orbit\Controllers;

use Orbit\Core\Redirect;
use Orbit\Core\View;
use Orbit\Models\PsychologyProfile;
use Orbit\Security\Auth;
use Orbit\Security\Csrf;

final class PsychologyController
{
    public function edit(): void
    {
        $userId = $this->requireUser();

        View::render('onboarding/psychology', [
            'title' => 'Connection style',
            'profile' => PsychologyProfile::findByUserId($userId) ?? [],
        ]);
    }

    public function save(): void
    {
        $userId = $this->requireUser();

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(419);
            echo 'Security token expired.';
            return;
        }

        $required = ['communication_style', 'social_energy', 'conflict_style', 'humour_style'];
        $errors = [];

        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[] = 'Please complete all connection style questions.';
                break;
            }
        }

        if ($errors !== []) {
            View::render('onboarding/psychology', [
                'title' => 'Connection style',
                'errors' => $errors,
                'profile' => $_POST,
            ]);
            return;
        }

        PsychologyProfile::save($userId, $_POST);
        Redirect::to('/matches');
    }

    private function requireUser(): int
    {
        if (!Auth::check()) {
            Redirect::to('/login');
        }

        return (int) Auth::id();
    }
}
