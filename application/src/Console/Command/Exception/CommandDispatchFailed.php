<?php

declare(strict_types=1);

namespace App\Console\Command\Exception;

use Exception;
use RuntimeException;
use Symfony\Component\Messenger\Envelope;
use Teapot\StatusCode;

final class CommandDispatchFailed extends RuntimeException
{
    /** @var string  */
    public const MSG = 'Failed to send message to message bus';

    /** @var Envelope */
    private Envelope $envelope;

    /**
     * @param Envelope $envelope
     * @param Exception|null $e
     * @return CommandDispatchFailed
     */
    public static function fromEnvelope(Envelope $envelope, Exception $e = null): self
    {
        $exception = new static(
            static::MSG,
            StatusCode::INTERNAL_SERVER_ERROR,
            $e
        );

        $exception->envelope = $envelope;

        return $exception;
    }

    /**
     * @return Envelope
     */
    public function getEnvelope(): Envelope
    {
        return $this->envelope;
    }
}
