<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\ContainerLoader\CacheInvalidator;

use function filter_var;
use function function_exists;
use function ini_get;
use function opcache_invalidate;

final class OpcacheInvalidator implements CacheInvalidatorInterface
{
    /**
     * @param string $path
     */
    public function invalidate(string $path): void
    {
        if (!function_exists('opcache_invalidate')) {
            return;
        }
        if (filter_var(ini_get('opcache.enable'), FILTER_VALIDATE_BOOLEAN)) {
            opcache_invalidate($path, true);
        }
    }
}
