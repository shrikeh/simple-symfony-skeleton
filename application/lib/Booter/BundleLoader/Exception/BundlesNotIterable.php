<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\BundleLoader\Exception;

use RuntimeException;

final class BundlesNotIterable extends RuntimeException
{
    /**
     * @return BundlesNotIterable
     */
    public static function create(): self
    {
        return new self('The bundles returned were not iterable');
    }
}
