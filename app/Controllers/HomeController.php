<?php

declare(strict_types=1);

namespace Orbit\Controllers;

use Orbit\Core\Redirect;
use Orbit\Core\View;
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

        View::render('member/home', ['title' => 'Your ORBIT']);
    }
}
