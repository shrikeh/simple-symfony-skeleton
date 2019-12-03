<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
require __DIR__ . '/env.php';

set_env($_SERVER, $_ENV);
