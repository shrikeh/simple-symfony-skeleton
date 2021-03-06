<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\BundleLoader;

interface BundlerLoaderInterface
{
    /** @var string  */
    public const ENVIRONMENTS_ALL = 'all';

    /**
     * @return iterable
     */
    public function getBundles(): iterable;
}
