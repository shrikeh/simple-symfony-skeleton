<?php

declare(strict_types=1);

namespace spec\Tests\Utils\TestCaseBundle\Message;

use PhpSpec\ObjectBehavior;
use Tests\Utils\TestCaseBundle\Message\CreateUnitTestMessage;

final class CreateUnitTestMessageSpec extends ObjectBehavior
{
    public function it_is_json_serializable(): void
    {
        $fqn = 'Foo\Bar\Baz';
        $this->beConstructedWith($fqn);
        $this->jsonSerialize()->shouldReturn([
            CreateUnitTestMessage::KEY_SUBJECT => $fqn,
        ]);
    }

    public function it_returns_the_test_subject(): void
    {
        $fqn = 'Flibble\Bibble\Bobble';
        $this->beConstructedWith($fqn);

        $this->getTestSubject()->shouldReturn($fqn);
    }
}
