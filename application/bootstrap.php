<?php

declare(strict_types=1);

$classLoader = require dirname(__DIR__) . '/vendor/autoload.php';

require_once __DIR__ . '/Env.php';

Env::set($_SERVER, $_ENV);
Env::setAutoloader($classLoader);

return $classLoader;
