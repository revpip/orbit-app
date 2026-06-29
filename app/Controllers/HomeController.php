<?php

declare(strict_types=1);

namespace Orbit\Controllers;

use Orbit\Core\Redirect;
use Orbit\Core\View;
use Orbit\Models\Intent;
use Orbit\Models\Profile;
use Orbit\Security\Auth;

final class HomeController
{
    public function index(): void
    {
        View::render('home/index', ['title' => 'ORBIT']);
    }

    public function memberHome(): void
    {
        if (!Auth::check()) {
            Redirect::to('/login');
        }

        $userId = (int) Auth::id();
        $profile = Profile::findByUserId($userId);

        if (!$profile) {
            Redirect::to('/onboarding/profile');
        }

        View::render('member/home', [
            'title' => 'Your ORBIT',
            'profile' => $profile,
            'selectedIntents' => Intent::forUser($userId),
            'allIntents' => Intent::active(),
        ]);
    }
}
