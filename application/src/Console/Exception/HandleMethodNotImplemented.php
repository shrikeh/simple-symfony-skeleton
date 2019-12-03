<?php

declare(strict_types=1);

namespace App\Console\Exception;

use BadMethodCallException;

final class HandleMethodNotImplemented extends BadMethodCallException
{
    /** @var string  */
    public const MESSAGE = 'The method Kernel::handle() is not implemented in the console context';

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::MESSAGE);
    }
}