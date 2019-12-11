<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache;

use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

interface ContainerCacheInterface
{
    /**
     * @param EnvironmentInterface $environment
     * @return ContainerInterface|null
     */
    public function loadCachedContainer(EnvironmentInterface $environment): ?ContainerInterface;

    /**
     * @param ContainerBuilder $container
     * @return ContainerInterface
     */
    public function saveContainerBuilder(ContainerBuilder $container): ContainerInterface;
}
