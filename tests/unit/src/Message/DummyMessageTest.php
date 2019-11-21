<?php

declare(strict_types=1);

namespace Tests\Mock\Console\Command;

use App\Message\DummyMessage;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

final class DummyMessageTest extends TestCase
{
    public function testItReturnsTheDummyMessage(): void
    {
        $string = 'baz bar boo';
        $dummyMessage = new DummyMessage($string);

        $this->assertSame($string, $dummyMessage->getDummyMessage());
    }

    public function testIsJsonSerializable(): void
    {
        $string = 'foo bar baz';
        $dummyMessage = new DummyMessage($string);
        $this->assertInstanceOf(JsonSerializable::class, $dummyMessage);
        $this->assertSame([DummyMessage::KEY_MSG => $string], $dummyMessage->jsonSerialize());
    }
}
