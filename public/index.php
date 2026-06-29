<?php

declare(strict_types=1);

use Orbit\Core\Application;

require dirname(__DIR__) . '/app/bootstrap.php';

$app = new Application();
$app->run();
