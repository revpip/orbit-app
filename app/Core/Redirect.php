<?php

declare(strict_types=1);

namespace Orbit\Core;

final class Redirect
{
    public static function to(string $path): never
    {
        header('Location: ' . $path, true, 302);
        exit;
    }
}
