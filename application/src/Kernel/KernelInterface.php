<?php

declare(strict_types=1);

namespace App\Kernel;

interface KernelInterface
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
}
