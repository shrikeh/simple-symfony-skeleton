<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper\Factory;

use SplFileObject;
use Symfony\Component\Config\ConfigCacheInterface;

interface ConfigCacheFactoryInterface
{
    /**
     * @param SplFileObject $lock
     * @param bool $debug
     * @return ConfigCacheInterface
     */
    public function create(SplFileObject $lock, bool $debug): ConfigCacheInterface;
}
