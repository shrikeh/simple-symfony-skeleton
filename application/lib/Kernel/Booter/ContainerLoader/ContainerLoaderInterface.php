<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

interface ContainerLoaderInterface
{
    /**
     * @param KernelInterface $kernel
     * @return ContainerInterface
     */
    public function loadContainer(KernelInterface $kernel): ContainerInterface;
}
