<?php

declare(strict_types=1);

namespace spec\TechTest\BusinessLogic\HelloWorld;

use PhpSpec\ObjectBehavior;
use Shrikeh\TestSymfonyApp\Dummy\DummyService;
use TechTest\BusinessLogic\HelloWorld\HelloWorldService;

final class HelloWorldServiceSpec extends ObjectBehavior
{
    public function it_is_initalisable(): void
    {
        $this->shouldHaveType(HelloWorldService::class);
    }

    public function it_returns_the_dummy_msg(): void
    {
        $dummyMsg = 'foo bar baz';
        $this->dummyMessage($dummyMsg)->shouldReturn($dummyMsg);
    }
}
