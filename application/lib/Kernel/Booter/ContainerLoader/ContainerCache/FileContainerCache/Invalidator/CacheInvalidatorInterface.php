<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Invalidator;

interface CacheInvalidatorInterface
{
    /**
     * @param string $path
     */
    public function invalidate(string $path): void;
}
