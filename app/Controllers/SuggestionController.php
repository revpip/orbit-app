<?php

declare(strict_types=1);

namespace Orbit\Controllers;

use Orbit\Core\Redirect;
use Orbit\Core\View;
use Orbit\Models\MatchSuggestion;
use Orbit\Models\PsychologyProfile;
use Orbit\Security\Auth;

final class SuggestionController
{
    public function index(): void
    {
        if (!Auth::check()) {
            Redirect::to('/login');
        }

        $userId = (int) Auth::id();

        if (!PsychologyProfile::findByUserId($userId)) {
            Redirect::to('/onboarding/psychology');
        }

        View::render('matches/index', [
            'title' => 'Your suggestions',
            'matches' => MatchSuggestion::forUser($userId),
        ]);
    }
}
