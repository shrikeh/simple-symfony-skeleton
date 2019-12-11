<?php

declare(strict_types=1);

if (!function_exists('xdebug_set_filter')) {
    return;
}

$applicationDir = dirname(__DIR__) . '/application/%s';

xdebug_set_filter(
    XDEBUG_FILTER_CODE_COVERAGE,
    XDEBUG_PATH_WHITELIST,
    [
        sprintf($applicationDir, 'app'),
        sprintf($applicationDir, 'src'),
        sprintf($applicationDir, 'lib'),
    ]
);
