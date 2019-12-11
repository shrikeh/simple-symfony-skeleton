<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Traits;

use Shrikeh\TestSymfonyApp\Kernel\ConfigurationLoader\ConfigurationLoaderInterface;
use Symfony\Component\Config\Loader\LoaderInterface;

trait ConfigurationLoaderTrait
{
    /**
     * @var ConfigurationLoaderInterface
     */
    private ConfigurationLoaderInterface $configurationLoader;


    /**
     * {@inheritDoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $this->configurationLoader->loadConfig($loader);
    }
}
