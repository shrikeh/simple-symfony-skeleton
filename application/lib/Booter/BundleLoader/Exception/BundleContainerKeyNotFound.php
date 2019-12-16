<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\BundleLoader\Exception;

use RuntimeException;

final class BundleContainerKeyNotFound extends RuntimeException
{
    /**
     * @param string $key
     * @return BundleContainerKeyNotFound
     */
    public static function fromKey(string $key): self
    {
        return new self(sprintf(
            'The PSR-11 container did not have the key %s',
            $key
        ));
    }
}
