<?php

declare(strict_types=1);

namespace App\Message;

use JsonSerializable;

final class HelloWorldMessage implements JsonSerializable
{
    public const KEY_MSG = 'dummy_message';

    /** @var string  */
    private string $dummyMessage;

    /**
     * HelloWorldMessage constructor.
     * @param string $dummyMessage
     */
    public function __construct(string $dummyMessage)
    {
        $this->dummyMessage = $dummyMessage;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return [
            static::KEY_MSG => $this->getDummyMessage(),
        ];
    }

    /**
     * @return string
     */
    public function getDummyMessage(): string
    {
        return $this->dummyMessage;
    }
}
