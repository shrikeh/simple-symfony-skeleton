<?php

use App\Kernel\Environment\EnvironmentInterface;
use Composer\Autoload\ClassLoader;

define('PROJECT_DIR', __DIR__);

final class Env
{
    private static ClassLoader $autoloader;

    /**
     * @return ClassLoader
     */
    public static function getAutoloader(): ClassLoader
    {
        return self::$autoloader;
    }

    /**
     * @param ClassLoader $classLoader
     */
    public static function setAutoloader(Classloader $classLoader): void
    {
        self::$autoloader = $classLoader;
    }

    /**
     * @param array $server
     * @param array $env
     */
    public static function set(array &$server, array &$env): void
    {
        $server += $env;
        $server['APP_ENV'] = $env['APP_ENV'] = ($server['APP_ENV'] ?? $env['APP_ENV'] ?? null) ?: EnvironmentInterface::ENV_DEV;
        $server['APP_DEBUG'] = $server['APP_DEBUG'] ?? $env['APP_DEBUG'] ?? 'prod' !== $server['APP_ENV'];
        $server['APP_DEBUG'] = $env['APP_DEBUG'] = (int) $server['APP_DEBUG'] || filter_var($server['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
    }
}
