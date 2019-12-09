<?php

declare(strict_types=1);

namespace spec\Tests\Utils\UnitTest\NamespaceCollection;

use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\NamespaceCollection\NamespaceSort;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class NamespaceSortSpec extends ObjectBehavior
{
    public function it_returns_one_if_the_second_fqn_is_longer(): void
    {
        $first = ClassNamespace::fromNamespaceString('Tic\Tac\Toe');
        $second = ClassNamespace::fromNamespaceString('Tic\Tac\Toe\Foo');

        $this($first, $second)->shouldReturn(1);
    }

    public function it_returns_minus_one_if_the_second_fqn_is_shorter(): void
    {
        $first = ClassNamespace::fromNamespaceString('Tic\Tac\Toe');
        $second = ClassNamespace::fromNamespaceString('Tic\Tac\Toe\Foo');

        $this($second, $first)->shouldReturn(-1);
    }

    public function it_returns_zero_if_the_values_are_equal(): void
    {
        $first = ClassNamespace::fromNamespaceString('Tic\Tac\Toe');
        $second = ClassNamespace::fromNamespaceString('Foo\Bar\Baz');

        $this($second, $first)->shouldReturn(0);
    }
}
