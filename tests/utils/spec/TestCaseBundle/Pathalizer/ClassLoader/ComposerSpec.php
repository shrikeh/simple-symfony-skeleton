<?php

declare(strict_types=1);

namespace spec\Tests\Utils\TestCaseBundle\Pathalizer\ClassLoader;

use Composer\Autoload\ClassLoader;
use PhpSpec\ObjectBehavior;
use Tests\Utils\Matcher\MatchFqnPath;
use Tests\Utils\UnitTest\ClassNamespace;

final class ComposerSpec extends ObjectBehavior
{
    public function getMatchers(): array
    {
        return [
            MatchFqnPath::NAME => new MatchFqnPath(),
        ];
    }

    public function it_returns_a_fqn_path(ClassLoader $classLoader): void
    {
        $this->beConstructedWith($classLoader);

        $targetDir = dirname(__DIR__);

        $targetParentFqn = 'Foo\Bar';
        
        $classLoader->getPrefixesPsr4()->willReturn([
            'Tic\Tac' => [__DIR__],
            $targetParentFqn => [$targetDir],
            'Foo\Boo\Bar' => '/not/ever/used'
        ]);

        $classFqn = ClassNamespace::fromNamespaceString('Foo\Bar\Bibble');

        $this->getFqnPathForClass($classFqn)->shouldMatchFqnPath(
            $targetParentFqn,
            $targetDir
        );
    }
}
