<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Test;

final class TestService
{
    /**
     * @param string $testMessage
     * @return string
     */
    public function testMessage(string $testMessage): string
    {
        return $testMessage;
    }
}
