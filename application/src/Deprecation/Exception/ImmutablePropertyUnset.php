<?php

declare(strict_types=1);

namespace App\Deprecation\Exception;

use BadMethodCallException;

final class ImmutablePropertyUnset extends BadMethodCallException
{
    /**
     * @param string $property
     * @return ImmutablePropertyModification
     */
    public static function create(string $property): self
    {
        return new self(sprintf(
            'The property "%s" is immutable. You cannot unset it',
            $property
        ));
    }
}
