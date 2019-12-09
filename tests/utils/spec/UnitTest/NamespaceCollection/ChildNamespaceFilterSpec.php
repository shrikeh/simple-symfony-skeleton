<?php

declare(strict_types=1);

namespace spec\Tests\Utils\UnitTest\NamespaceCollection;

use PhpSpec\ObjectBehavior;
use Tests\Utils\UnitTest\ClassNamespace;

final class ChildNamespaceFilterSpec extends ObjectBehavior
{
    public function it_returns_true_if_it_is_a_child_pf_the_namespace(): void
    {
        $childFqn = ClassNamespace::fromNamespaceString('Tic\Tac\Toe');
        
        $this->beConstructedWith($childFqn);

        $this(ClassNamespace::fromNamespaceString('Tic\Tac'), [])->shouldReturn(true);
    }

    public function it_returns_false_if_it_is_not_a_child_pf_the_namespace(): void
    {
        $childFqn = ClassNamespace::fromNamespaceString('Tic\Tac\Toe');

        $this->beConstructedWith($childFqn);

        $this(ClassNamespace::fromNamespaceString('Foo\Bar'), [])->shouldReturn(false);
    }
}
