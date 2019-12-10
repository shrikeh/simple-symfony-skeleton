<?php

declare(strict_types=1);

namespace spec\Tests\Utils\TestCaseBundle\Pathalizer;

use PhpSpec\ObjectBehavior;
use Tests\Utils\Matcher\MatchSplFileInfoPath;
use Tests\Utils\TestCaseBundle\Pathalizer\FqnPath;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\TestCaseBundle\Pathalizer\ClassLoader\ClassLoaderInterface;

final class Psr4PathalizerSpec extends ObjectBehavior
{
    public function getMatchers(): array
    {
        return [
            MatchSplFileInfoPath::NAME => new MatchSplFileInfoPath(),
        ];
    }

    public function it_returns_the_test_case_path(ClassLoaderInterface $classLoader): void
    {
        $this->beConstructedWith($classLoader);
        $classNamespace = ClassNamespace::fromNamespaceString('Some\Test\NamespaceTest');
        $fqnPath = FqnPath::fromString('Some\Test', __DIR__);
        $classLoader->getFqnPathForClass($classNamespace)->willReturn($fqnPath);

        $this->getUnitTestPathFor($classNamespace)->shouldMatchSplFilePath(
            __DIR__ . '/NamespaceTest.php'
        );
    }
}
