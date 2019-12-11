<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\ConfigurationLoader;

use Symfony\Component\Config\Loader\LoaderInterface;

interface ConfigurationLoaderInterface
{
    /**
     * @param LoaderInterface $loader
     */
    public function loadConfig(LoaderInterface $loader): void;
}
