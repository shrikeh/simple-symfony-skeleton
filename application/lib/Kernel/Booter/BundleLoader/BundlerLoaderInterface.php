<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Booter\BundleLoader;

interface BundlerLoaderInterface
{
    /**
     * @return iterable
     */
    public function getBundles(): iterable;
}
