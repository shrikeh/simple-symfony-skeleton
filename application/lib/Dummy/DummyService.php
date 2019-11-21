<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Dummy;

final class DummyService
{
    /**
     * @param string $dummyMessage
     * @return string
     */
    public function dummyMessage(string $dummyMessage): string
    {
        return $dummyMessage;
    }
}
