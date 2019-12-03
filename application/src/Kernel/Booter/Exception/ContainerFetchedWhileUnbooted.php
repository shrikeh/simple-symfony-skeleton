<?php

declare(strict_types=1);

namespace App\Kernel\Booter\Exception;

use RuntimeException;

final class ContainerFetchedWhileUnbooted extends RuntimeException
{
    /** @var string  */
    public const MESSAGE = 'Fetching the container without first booting is a no-no';

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::MESSAGE);
    }
}
