<?php

declare(strict_types=1);

$classLoader = require dirname(__DIR__) . '/vendor/autoload.php';

use App\Bootstrap;

Bootstrap::loadDotEnv(dirname(__DIR__) . '/.env');

Bootstrap::set($_SERVER, $_ENV);
Bootstrap::setAutoloader($classLoader);

return Bootstrap::getAutoloader();
