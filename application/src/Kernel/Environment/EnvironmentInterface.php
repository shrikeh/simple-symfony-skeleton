<?php

declare(strict_types=1);

namespace App\Kernel\Environment;

use Symfony\Component\HttpFoundation\ServerBag;

interface EnvironmentInterface
{
    /** @var string  */
    public const ENV_DEV = 'dev';
    /** @var string  */
    public const ENV_PROD = 'prod';
    /** @var string  */
    public const ENV_TEST = 'test';

    /** @var array  */
    public const ENVS_PRE_PROD = [
        self::ENV_DEV,
        self::ENV_TEST,
    ];
    /** @var array  */
    public const ALLOWED_ENVS = [
        self::ENV_DEV,
        self::ENV_TEST,
        self::ENV_PROD,
    ];

    /**
     * @return string
     */
    public function getEnvironmentName(): string;

    /**
     * @return string
     */
    public function getCharset(): string;

    /**
     * @return bool
     */
    public function isDebug(): bool;

    /**
     * @return ServerBag
     */
    public function getServerBag(): ServerBag;

    /**
     * Set the shell verbosity
     */
    public function setDebugShellVerbosity(): void;
}
