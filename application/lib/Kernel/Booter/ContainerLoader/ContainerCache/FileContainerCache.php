<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache;

use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\CachePath;
use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class FileContainerCache implements ContainerCacheInterface
{
    /** @var CachePath  */
    private CachePath $cache;

    /**
     * @var FileContainerCache\Dumper\ContainerDumperInterface
     */
    private FileContainerCache\Dumper\ContainerDumperInterface $containerDumper;

    /**
     * FileContainerCache constructor.
     * @param CachePath $cache
     * @param FileContainerCache\Dumper\ContainerDumperInterface $containerDumper
     */
    public function __construct(
        CachePath $cache,
        FileContainerCache\Dumper\ContainerDumperInterface $containerDumper
    ) {
        $this->cache = $cache;
        $this->containerDumper = $containerDumper;
    }

    /**
     * {@inheritDoc}
     */
    public function loadCachedContainer(EnvironmentInterface $environment): ?ContainerInterface
    {
        $container = $this->cache->read();

        return $this->isValid($environment, $container) ? $container : null;
    }

    /**
     * {@inheritDoc}
     */
    public function saveContainerBuilder(ContainerBuilder $container): ContainerInterface
    {
        return $this->containerDumper->dumpContainer($container);
    }


    /**
     * @param EnvironmentInterface $environment
     * @param object|null $container
     * @return bool
     */
    private function isValid(EnvironmentInterface $environment, object $container = null): bool
    {
        return !(null !== $container || $environment->isDebug());
    }
}
