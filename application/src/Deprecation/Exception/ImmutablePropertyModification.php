<?php

declare(strict_types=1);

namespace App\Deprecation\Exception;

use BadMethodCallException;

final class ImmutablePropertyModification extends BadMethodCallException
{
    /**
     * @param string $property
     * @param string $value
     * @return ImmutablePropertyModification
     */
    public static function create(string $property, string $value): self
    {
        return new self(sprintf(
            'The property "%s" is immutable. You cannot modify it with a value of "%s"',
            $property,
            $value
        ));
    }
}
