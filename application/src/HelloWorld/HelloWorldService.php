<?php

declare(strict_types=1);

namespace TechTest\BusinessLogic\HelloWorld;

final class HelloWorldService
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
