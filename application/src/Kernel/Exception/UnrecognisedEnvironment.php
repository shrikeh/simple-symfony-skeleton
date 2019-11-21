<?php

declare(strict_types=1);

namespace App\Kernel\Exception;

use InvalidArgumentException;

final class UnrecognisedEnvironment extends InvalidArgumentException
{
    /**
     * @param string $environment
     * @return UnrecognisedEnvironment
     */
    public static function create(string $environment): self
    {
        return new self(sprintf('The environment "%s" is not recognised.', $environment));
    }
}
