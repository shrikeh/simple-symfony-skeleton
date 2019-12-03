<?php

declare(strict_types=1);

namespace App\Kernel\Traits;

use App\Kernel\Booter\BooterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait BooterTrait
{
    /**
     * @var BooterInterface
     */
    private BooterInterface $booter;

    /**
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booter->isBooted();
    }

    /**
     * {@inheritDoc}
     */
    public function registerBundles(): iterable
    {
        return $this->booter->getBundles();
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        $this->booter->boot($this);
    }

    /**
     * {@inheritDoc}
     */
    public function shutdown(): void
    {
        if (!$this->booter->isBooted()) {
            return;
        }

        $this->booter->shutdown();
    }

    /**
     * {@inheritDoc}
     */
    public function getContainer(): ContainerInterface
    {
        return $this->booter->getContainer();
    }


    /**
     * {@inheritDoc}
     */
    public function getBundles(): iterable
    {
        return $this->booter->getBundles();
    }
}