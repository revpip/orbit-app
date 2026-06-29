<?php

declare(strict_types=1);

namespace Orbit\Controllers;

use Orbit\Core\Redirect;
use Orbit\Core\View;
use Orbit\Models\Intent;
use Orbit\Models\Profile;
use Orbit\Security\Auth;
use Orbit\Security\Csrf;

final class OnboardingController
{
    public function profile(): void
    {
        $userId = $this->requireUser();

        View::render('onboarding/profile', [
            'title' => 'Create your profile',
            'profile' => Profile::findByUserId($userId) ?? [],
        ]);
    }

    public function saveProfile(): void
    {
        $userId = $this->requireUser();

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(419);
            echo 'Security token expired.';
            return;
        }

        $errors = $this->validateProfile($_POST);

        if ($errors !== []) {
            View::render('onboarding/profile', [
                'title' => 'Create your profile',
                'errors' => $errors,
                'profile' => $_POST,
            ]);
            return;
        }

        Profile::save($userId, $_POST);
        Redirect::to('/onboarding/intents');
    }

    public function intents(): void
    {
        $userId = $this->requireUser();

        View::render('onboarding/intents', [
            'title' => 'Choose your connection intents',
            'intents' => Intent::active(),
            'selected' => Intent::forUser($userId),
        ]);
    }

    public function saveIntents(): void
    {
        $userId = $this->requireUser();

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(419);
            echo 'Security token expired.';
            return;
        }

        $intentIds = $_POST['intent_ids'] ?? [];

        if (!is_array($intentIds) || count($intentIds) < 1) {
            View::render('onboarding/intents', [
                'title' => 'Choose your connection intents',
                'errors' => ['Please choose at least one connection intent.'],
                'intents' => Intent::active(),
                'selected' => [],
            ]);
            return;
        }

        Intent::syncForUser($userId, $intentIds);
        Redirect::to('/onboarding/psychology');
    }

    private function requireUser(): int
    {
        if (!Auth::check()) {
            Redirect::to('/login');
        }

        return (int) Auth::id();
    }

    private function validateProfile(array $data): array
    {
        $errors = [];

        if (mb_strlen(trim((string) ($data['headline'] ?? ''))) < 4) {
            $errors[] = 'Please add a short profile headline.';
        }

        if (mb_strlen(trim((string) ($data['bio'] ?? ''))) < 20) {
            $errors[] = 'Please add a warmer introduction of at least 20 characters.';
        }

        if (empty($data['town'])) {
            $errors[] = 'Please add your town or nearest area.';
        }

        if (empty($data['postcode_prefix'])) {
            $errors[] = 'Please add the first part of your postcode.';
        }

        return $errors;
    }
}
