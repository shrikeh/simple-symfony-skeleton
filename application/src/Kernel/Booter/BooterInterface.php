<?php

declare(strict_types=1);

namespace App\Kernel\Booter;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

interface BooterInterface
{
    /**
     * Remove the container and shutdown
     */
    public function shutdown(): void;

    /**
     * Boot the bundles and container
     * @param KernelInterface $kernel
     */
    public function boot(KernelInterface $kernel): void;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * @return iterable
     */
    public function getBundles(): iterable;
}
