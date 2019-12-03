<?php

declare(strict_types=1);

namespace spec\Shrikeh\TestSymfonyApp\Dummy;

use PhpSpec\ObjectBehavior;
use Shrikeh\TestSymfonyApp\Dummy\DummyService;

final class DummyServiceSpec extends ObjectBehavior
{
    public function it_is_initalisable(): void
    {
        $this->shouldHaveType(DummyService::class);
    }

    public function it_returns_the_dummy_msg(): void
    {
        $dummyMsg = 'foo bar baz';
        $this->dummyMessage($dummyMsg)->shouldReturn($dummyMsg);
    }
}
