<?php

declare(strict_types=1);

namespace App\Kernel\Booter\ContainerLoader\ContainerCache\Invalidator;

interface CacheInvalidatorInterface
{
    /**
     * @param string $path
     */
    public function invalidate(string $path): void;
}
