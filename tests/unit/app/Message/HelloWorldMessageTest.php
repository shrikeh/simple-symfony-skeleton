<?php

declare(strict_types=1);

namespace Tests\Unit\App\Message;

use App\Message\HelloWorldMessage;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

final class HelloWorldMessageTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsTheHelloWorldMessage(): void
    {
        $string = 'baz bar boo';
        $dummyMessage = new HelloWorldMessage($string);

        $this->assertSame($string, $dummyMessage->getDummyMessage());
    }

    /**
     * @test
     */
    public function itIsJsonSerializable(): void
    {
        $string = 'foo bar baz';
        $dummyMessage = new HelloWorldMessage($string);
        $this->assertInstanceOf(JsonSerializable::class, $dummyMessage);
        $this->assertSame([HelloWorldMessage::KEY_MSG => $string], $dummyMessage->jsonSerialize());
    }
}
