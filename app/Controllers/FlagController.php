<?php

declare(strict_types=1);

namespace Orbit\Controllers;

use Orbit\Core\Redirect;
use Orbit\Core\View;
use Orbit\Models\UserFlag;
use Orbit\Security\Auth;
use Orbit\Security\Csrf;

final class FlagController
{
    public function create(): void
    {
        if (!Auth::check()) {
            Redirect::to('/login');
        }

        View::render('safety/flag', [
            'title' => 'Flag a concern',
            'targetUserId' => (int) ($_GET['user_id'] ?? 0),
        ]);
    }

    public function store(): void
    {
        if (!Auth::check()) {
            Redirect::to('/login');
        }

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(419);
            echo 'Security token expired.';
            return;
        }

        $targetUserId = (int) ($_POST['target_user_id'] ?? 0);
        $reason = trim((string) ($_POST['reason'] ?? ''));
        $details = trim((string) ($_POST['details'] ?? ''));

        if ($targetUserId < 1 || $targetUserId === (int) Auth::id() || $reason === '') {
            View::render('safety/flag', [
                'title' => 'Flag a concern',
                'errors' => ['Please choose a reason.'],
                'targetUserId' => $targetUserId,
                'old' => $_POST,
            ]);
            return;
        }

        UserFlag::create((int) Auth::id(), $targetUserId, $reason, $details);
        Redirect::to('/safety/thanks');
    }

    public function thanks(): void
    {
        View::render('safety/thanks', ['title' => 'Thank you']);
    }
}
