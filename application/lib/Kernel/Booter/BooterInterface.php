<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Booter;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

interface BooterInterface
{
    /**
     * Remove the container and shutdown
     */
    public function shutdown(): void;

    /**
     * @return bool
     */
    public function isBooted(): bool;

    /**
     * Boot the bundles and container
     * @param KernelInterface $kernel
     * @param bool $reboot
     */
    public function boot(KernelInterface $kernel, bool $reboot = false): void;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * @return iterable
     */
    public function getBundles(): iterable;
}
