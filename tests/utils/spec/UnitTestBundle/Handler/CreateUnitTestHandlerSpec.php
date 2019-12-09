<?php

declare(strict_types=1);

namespace spec\Tests\Utils\UnitTestBundle\Handler;

use Tests\Utils\UnitTestBundle\Handler\CreateUnitTestHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class CreateUnitTestHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CreateUnitTestHandler::class);
    }
}
