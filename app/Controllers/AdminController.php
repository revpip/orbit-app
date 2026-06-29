<?php

declare(strict_types=1);

namespace Orbit\Controllers;

use Orbit\Core\Redirect;
use Orbit\Core\View;
use Orbit\Models\UserFlag;
use Orbit\Security\Auth;

final class AdminController
{
    public function flags(): void
    {
        if (!Auth::check()) {
            Redirect::to('/login');
        }

        View::render('admin/flags', [
            'title' => 'Review queue',
            'flags' => UserFlag::open(),
        ]);
    }
}
