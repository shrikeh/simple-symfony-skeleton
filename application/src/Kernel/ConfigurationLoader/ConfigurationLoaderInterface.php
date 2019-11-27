<?php

declare(strict_types=1);

namespace App\Kernel\ConfigurationLoader;

use Symfony\Component\Config\Loader\LoaderInterface;

interface ConfigurationLoaderInterface
{
    /**
     * @param LoaderInterface $loader
     * @throws \Exception
     */
    public function loadConfig(LoaderInterface $loader): void;
}
