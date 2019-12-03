<?php
declare(strict_types=1);

namespace spec\Shrikeh\TestSymfonyApp\Dummy;

use Shrikeh\TestSymfonyApp\Dummy\DummyService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DummyServiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DummyService::class);
    }
}
