<?php

use App\Kernel\Environment\EnvironmentInterface;

define('PROJECT_DIR', __DIR__);

/**
 * @param array $server
 * @param array $env
 */
function set_env(array &$server, array &$env): void
{
    $server += $env;
    $server['APP_ENV'] = $env['APP_ENV'] = ($server['APP_ENV'] ?? $env['APP_ENV'] ?? null) ?: EnvironmentInterface::ENV_DEV;
    $server['APP_DEBUG'] = $server['APP_DEBUG'] ?? $env['APP_DEBUG'] ?? 'prod' !== $server['APP_ENV'];
    $server['APP_DEBUG'] = $env['APP_DEBUG'] = (int) $server['APP_DEBUG'] || filter_var($server['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
}
