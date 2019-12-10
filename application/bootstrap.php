<?php

declare(strict_types=1);

$classLoader = require dirname(__DIR__) . '/vendor/autoload.php';

require_once __DIR__ . '/Env.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();

// You can also load several files
$dotenv->load(__DIR__ . '/.env.local');

Env::set($_SERVER, $_ENV);
Env::setAutoloader($classLoader);

return $classLoader;
