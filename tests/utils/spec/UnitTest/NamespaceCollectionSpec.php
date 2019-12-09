<?php

declare(strict_types=1);

namespace spec\Tests\Utils\UnitTest;

use PhpSpec\ObjectBehavior;
use Tests\Utils\Matcher\MatchNamespace;
use Tests\Utils\UnitTest\ClassNamespace;

final class NamespaceCollectionSpec extends ObjectBehavior
{
    public function getMatchers(): array
    {
        return [
            MatchNamespace::NAME => new MatchNamespace(),
        ];
    }

    public function it_returns_the_best_match(): void
    {
        $this->beConstructedThroughFromArray([
            'Foo\Bar\Baz' => 'foo',
            'Foo\Bar' => 'bar',
            'Bar\Baz' => 'baz',
        ]);

        $this->match(ClassNamespace::fromNamespaceString('Foo\Bar\Bibble'))
            ->shouldMatchNamespace('Foo\Bar');
    }

    public function it_returns_the_metadata(): void
    {

    }
}
