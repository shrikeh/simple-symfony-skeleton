<?php

declare(strict_types=1);

namespace App\Kernel\Booter\BundleLoader;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface BundlerLoaderInterface
{
    /**
     * @return iterable
     */
    public function getBundles(): iterable;

    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function addContainerResource(ContainerBuilder $containerBuilder): void;
}
