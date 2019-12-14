<?php

declare(strict_types=1);

use Shrikeh\TestSymfonyApp\Bootstrap;

require_once dirname(__DIR__) . '/constants.php';

$classLoader = require VENDOR_DIR . '/autoload.php';

Bootstrap::loadDotEnv(__DIR__ . '/.env.behat');

Bootstrap::set($_SERVER, $_ENV);
Bootstrap::setAutoloader($classLoader);
