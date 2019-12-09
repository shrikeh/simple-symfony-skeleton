<?php

declare(strict_types=1);

namespace App\Deprecation\Exception;

use BadMethodCallException;

final class TypeNotDeprecation extends BadMethodCallException
{
    /**
     * @param int $type
     * @return ImmutablePropertyModification
     */
    public static function create(int $type): self
    {
        return new self(sprintf(
            'The type "%d" is not a E_USER_DEPRECATED or E_DEPRECATED error',
            $type
        ));
    }
}
