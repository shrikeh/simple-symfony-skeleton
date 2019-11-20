<?php

declare(strict_types=1);

namespace App\Message;

final class TestMessage
{
    /** @var string  */
    private string $testMessage;

    /**
     * TestMessage constructor.
     * @param string $testMessage
     */
    public function __construct(string $testMessage)
    {
        $this->testMessage = $testMessage;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'test_message' => $this->getTestMessage(),
        ];
    }

    /**
     * @return string
     */
    public function getTestMessage(): string
    {
        return $this->testMessage;
    }
}
