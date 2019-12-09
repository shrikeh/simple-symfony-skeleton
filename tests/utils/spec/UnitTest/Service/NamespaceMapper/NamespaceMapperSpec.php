<?php

declare(strict_types=1);

namespace spec\Tests\Utils\UnitTest\Service\NamespaceMapper;

use PhpSpec\ObjectBehavior;
use Tests\Utils\Matcher\MatchNamespace;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\NamespaceCollection;

final class NamespaceMapperSpec extends ObjectBehavior
{
    public function getMatchers(): array
    {
        return [
            MatchNamespace::NAME => new MatchNamespace(),
        ];
    }

    public function it_returns_a_test_namespace(): void
    {
        $this->beConstructedThroughFromArray([
            'Foo\Bar\Baz' => '\Tests',
        ]);

        $this->getTestCaseFqnFor(
            ClassNamespace::fromNamespaceString('Foo\Bar\Baz\Boo')
        )->shouldMatchNamespace('Tests\BooTest');
    }

    public function it_returns_the_closest_matching_test_namespace(): void
    {
        $this->beConstructedWith(NamespaceCollection::fromArray([
            '\Foo\Bar' => '\Tests',
            '\\Baz\\Boo\Bee\\' => '\\Testing\Dir\Desired',
            'Baz\Boo\\' => 'Some\Other\Test\Namespace',
        ]));

        $this->getTestCaseFqnFor(
            ClassNamespace::fromNamespaceString('Baz\Boo\Bee\Bop\Bibble')
        )->shouldMatchNamespace('Testing\Dir\Desired\Bop\BibbleTest');
    }
}
