<?php

declare(strict_types=1);

namespace Orbit\Controllers;

use Orbit\Core\Redirect;
use Orbit\Core\View;
use Orbit\Models\Report;
use Orbit\Security\Auth;

final class ModerationController
{
    public function queue(): void
    {
        if (!Auth::check()) {
            Redirect::to('/login');
        }

        View::render('moderation/queue', [
            'title' => 'Moderation queue',
            'reports' => Report::openReports(),
        ]);
    }
}
