<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper\Factory;

use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ConfigCache\AnonymousConfigCache;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ConfigCache\DebugConfigCache;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Invalidator\CacheInvalidatorInterface;
use SplFileObject;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\Filesystem\Filesystem;

final class ConfigCache implements ConfigCacheFactoryInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var CacheInvalidatorInterface
     */
    private CacheInvalidatorInterface $cacheInvalidator;

    /**
     * ConfigCache constructor.
     * @param Filesystem $filesystem
     * @param CacheInvalidatorInterface $cacheInvalidator
     */
    public function __construct(Filesystem $filesystem, CacheInvalidatorInterface $cacheInvalidator)
    {
        $this->filesystem = $filesystem;
        $this->cacheInvalidator = $cacheInvalidator;
    }

    /**
     * @param SplFileObject $lock
     * @param bool $debug
     * @return ConfigCacheInterface
     */
    public function create(SplFileObject $lock, bool $debug): ConfigCacheInterface
    {
        return $debug ? $this->createDebug($lock) : $this->createAnonymous($lock);
    }

    /**
     * @param SplFileObject $lock
     * @return DebugConfigCache
     */
    private function createDebug(SplFileObject $lock): DebugConfigCache
    {
        return new DebugConfigCache(
            $lock,
            $this->filesystem,
            $this->cacheInvalidator
        );
    }

    /**
     * @param SplFileObject $lock
     * @return AnonymousConfigCache
     */
    private function createAnonymous(SplFileObject $lock): AnonymousConfigCache
    {
        return new class (
            $lock,
            $this->filesystem,
            $this->cacheInvalidator
        ) extends AnonymousConfigCache {
        };
    }
}
