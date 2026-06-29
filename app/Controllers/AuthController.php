<?php

declare(strict_types=1);

namespace Orbit\Controllers;

use Orbit\Core\Redirect;
use Orbit\Core\View;
use Orbit\Models\User;
use Orbit\Security\Auth;
use Orbit\Security\Csrf;

final class AuthController
{
    public function showRegister(): void
    {
        View::render('auth/register', ['title' => 'Join ORBIT']);
    }

    public function register(): void
    {
        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(419);
            echo 'Security token expired.';
            return;
        }

        $email = trim((string) ($_POST['email'] ?? ''));
        $displayName = trim((string) ($_POST['display_name'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (mb_strlen($displayName) < 2) {
            $errors[] = 'Please enter the name you want to be known by.';
        }

        if (strlen($password) < 10) {
            $errors[] = 'Please use a password of at least 10 characters.';
        }

        if (User::findByEmail($email) !== null) {
            $errors[] = 'An account already exists for that email address.';
        }

        if ($errors !== []) {
            View::render('auth/register', [
                'title' => 'Join ORBIT',
                'errors' => $errors,
                'old' => ['email' => $email, 'display_name' => $displayName],
            ]);
            return;
        }

        $userId = User::create($email, $displayName, $password);
        Auth::login($userId);
        Redirect::to('/dashboard');
    }

    public function showLogin(): void
    {
        View::render('auth/login', ['title' => 'Sign in']);
    }

    public function login(): void
    {
        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(419);
            echo 'Security token expired.';
            return;
        }

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, (string) $user['password_hash'])) {
            View::render('auth/login', [
                'title' => 'Sign in',
                'errors' => ['Those login details were not recognised.'],
                'old' => ['email' => $email],
            ]);
            return;
        }

        Auth::login((int) $user['id']);
        Redirect::to('/dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        Redirect::to('/');
    }
}
