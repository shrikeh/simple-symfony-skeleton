<?php

declare(strict_types=1);

namespace spec\Tests\Utils\UnitTest;

use PhpSpec\ObjectBehavior;
use Tests\Utils\Matcher\MatchNamespace;
use Tests\Utils\UnitTest\ClassNamespace;

final class ClassNamespaceSpec extends ObjectBehavior
{
    public function getMatchers(): array
    {
        return [
            MatchNamespace::NAME => new MatchNamespace(),
        ];
    }

    public function it_returns_the_parent_namespace(): void
    {
        $this->beConstructedWith(['Foo', 'Bar', 'Baz']);

        $this->getParent()->shouldMatchNamespace('Foo\Bar');
    }

    public function it_returns_the_last_part(): void
    {
        $this->beConstructedWith(['Foo', 'Bar', 'Baz']);

        $this->getLastPart()->shouldReturn('Baz');
    }

    public function it_can_append_itself_to_another_namespace(): void
    {
        $this->beConstructedWith(['Foo', 'Bar', 'Baz']);
        $relativeNamespace = ClassNamespace::fromNamespaceString('Bibble\Bubble');

        $this->appendTo($relativeNamespace)->shouldMatchNamespace('Bibble\Bubble\Foo\Bar\Baz');
    }

    public function it_can_prepend_itself_to_another_namespace(): void
    {
        $this->beConstructedWith(['Boo', 'Baz', 'Bal']);
        $classPath = ClassNamespace::fromNamespaceString('Wibble\Wubble');

        $this->prependTo($classPath)->shouldMatchNamespace('Boo\Baz\Bal\Wibble\Wubble');
    }

    public function it_returns_a_fully_qualified_namespace(): void
    {
        $this->beConstructedWith(['Foo', 'Bar', 'Baz']);

        $this->toString()->shouldReturn('Foo\Bar\Baz');
    }

    public function it_returns_the_namespace_parts_in_order(): void
    {
        $this->beConstructedThroughFromNamespaceString('\Bar\Baz\Boo\Bibble');

        $this->getNamespaceParts()->shouldReturn([
            'Bar',
            'Baz',
            'Boo',
            'Bibble',
        ]);
    }

    public function it_strips_out_bad_values(): void
    {
        $this->beConstructedWith(['Foo', 'Bar', null, 'Baz', false, 'Boo', '', 'Bing']);

        $this->getNamespaceParts()->shouldReturn([
            'Foo',
            'Bar',
            'Baz',
            'Boo',
            'Bing',
        ]);
    }

    public function it_can_make_itself_relative_to_another_namespace(): void
    {
        $this->beConstructedThroughFromNamespaceString('\\Bar\\Baz\\Boo\\Bibble\\Bubble\\');
        $relativeNamespace = ClassNamespace::fromNamespaceString('Bar\Baz');

        $this->getRelativeNamespaceTo($relativeNamespace)->shouldMatchNamespace('Boo\Bibble\Bubble');
    }
}
