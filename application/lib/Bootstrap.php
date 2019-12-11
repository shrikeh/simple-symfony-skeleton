<?php

namespace Shrikeh\TestSymfonyApp;

use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Dotenv\Dotenv;

final class Bootstrap
{
    /** @var ClassLoader */
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
     * @param string $envFile
     * @return Dotenv
     */
    public static function loadDotEnv(string $envFile): Dotenv
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv($envFile);

        return $dotenv;
    }

    /**
     * @param array $server
     * @param array $env
     */
    public static function set(array &$server, array &$env): void
    {
        $server += $env;
        $server['APP_ENV'] = $env['APP_ENV'] = self::setAppEnv($server, $env);
        $server['APP_DEBUG'] = $server['APP_DEBUG'] ?? $env['APP_DEBUG'] ??
            EnvironmentInterface::ENV_PROD !== $server['APP_ENV'];
        $server['APP_DEBUG'] = $env['APP_DEBUG'] = self::setAppDebug($server);
    }

    /**
     * @param array $server
     * @param array $env
     * @return string
     */
    private static function setAppEnv(array $server, array $env): string
    {
        return ($server['APP_ENV'] ?? $env['APP_ENV'] ?? null) ?: EnvironmentInterface::ENV_DEV;
    }

    /**
     * @param array $server
     * @return bool
     */
    private static function setAppDebug(array $server): int
    {
        return (int) $server['APP_DEBUG'] || filter_var(
            $server['APP_DEBUG'],
            FILTER_VALIDATE_BOOLEAN
        ) ? '1' : '0';
    }
}
