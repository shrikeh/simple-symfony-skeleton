<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\ContainerLoader\CacheInvalidator;

interface CacheInvalidatorInterface
{
    /**
     * @param string $path
     */
    public function invalidate(string $path): void;
}
