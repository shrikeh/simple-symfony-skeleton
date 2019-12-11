<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

interface ContainerDumperInterface
{
    /**
     * @param ContainerBuilder $container
     * @param bool $debug
     * @return ContainerInterface
     */
    public function dumpContainer(ContainerBuilder $container, bool $debug = false): ContainerInterface;
}
