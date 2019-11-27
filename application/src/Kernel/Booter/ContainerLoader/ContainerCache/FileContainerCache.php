<?php

declare(strict_types=1);

namespace App\Kernel\Booter\ContainerLoader\ContainerCache;

use App\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\CachePath;
use App\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper\ContainerDumperInterface;
use App\Kernel\Environment\EnvironmentInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

use function is_object;

final class FileContainerCache implements ContainerCacheInterface
{
    /** @var CachePath  */
    private CachePath $cache;

    /**
     * @var ContainerDumperInterface
     */
    private ContainerDumperInterface $containerDumper;

    /**
     * FileContainerCache constructor.
     * @param CachePath $cache
     * @param ContainerDumperInterface $containerDumper
     */
    public function __construct(
        CachePath $cache,
        ContainerDumperInterface $containerDumper
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
