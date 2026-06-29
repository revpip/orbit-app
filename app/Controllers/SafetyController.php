<?php

declare(strict_types=1);

namespace Orbit\Controllers;

use Orbit\Core\Redirect;
use Orbit\Core\View;
use Orbit\Models\Report;
use Orbit\Models\UserBlock;
use Orbit\Security\Auth;
use Orbit\Security\Csrf;

final class SafetyController
{
    public function reportForm(): void
    {
        $this->requireUser();
        $reportedUserId = (int) ($_GET['user_id'] ?? 0);

        View::render('safety/report', [
            'title' => 'Report a concern',
            'reportedUserId' => $reportedUserId,
        ]);
    }

    public function submitReport(): void
    {
        $reporterId = $this->requireUser();

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(419);
            echo 'Security token expired.';
            return;
        }

        $reportedUserId = (int) ($_POST['reported_user_id'] ?? 0);
        $reason = trim((string) ($_POST['reason'] ?? ''));
        $details = trim((string) ($_POST['details'] ?? ''));

        if ($reportedUserId <= 0 || $reportedUserId === $reporterId || $reason === '') {
            View::render('safety/report', [
                'title' => 'Report a concern',
                'errors' => ['Please choose a reason for the report.'],
                'reportedUserId' => $reportedUserId,
            ]);
            return;
        }

        Report::create($reporterId, $reportedUserId, $reason, $details);
        Redirect::to('/safety/thanks');
    }

    public function block(): void
    {
        $blockerId = $this->requireUser();

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(419);
            echo 'Security token expired.';
            return;
        }

        $blockedUserId = (int) ($_POST['blocked_user_id'] ?? 0);

        if ($blockedUserId > 0 && $blockedUserId !== $blockerId) {
            UserBlock::block($blockerId, $blockedUserId);
        }

        Redirect::to('/matches');
    }

    public function thanks(): void
    {
        $this->requireUser();
        View::render('safety/thanks', ['title' => 'Thank you']);
    }

    private function requireUser(): int
    {
        if (!Auth::check()) {
            Redirect::to('/login');
        }

        return (int) Auth::id();
    }
}
