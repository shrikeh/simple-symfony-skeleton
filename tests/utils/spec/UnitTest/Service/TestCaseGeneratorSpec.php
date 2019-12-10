<?php

declare(strict_types=1);

namespace spec\Tests\Utils\UnitTest\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SplFileInfo;
use Tests\Utils\Matcher\MatchTestCase;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\Service\NamespaceMapper\NamespaceMapperInterface;
use Tests\Utils\UnitTest\Service\Pathalizer\PathalizerInterface;
use Tests\Utils\UnitTest\TestCase;
use Tests\Utils\TestCaseBundle\Message\CreateUnitTestMessage;

final class TestCaseGeneratorSpec extends ObjectBehavior
{
    public function getMatchers(): array
    {
        return [
            MatchTestCase::NAME => new MatchTestCase(),
        ];
    }

    public function it_returns_a_test_case(
        PathalizerInterface $pathalizer,
        NamespaceMapperInterface $namespaceMapper
    ): void {

        $subjectFqn = 'Some\Subject';
        $testCaseFqn = 'Tests\Utils\SubjectTest';

        $fileInfo = new SplFileInfo(__FILE__);

        $namespaceMapper->getTestCaseFqnFor(Argument::type(ClassNamespace::class))
            ->willReturn(ClassNamespace::fromNamespaceString($testCaseFqn));

        $pathalizer->getUnitTestPathFor($testCaseFqn)->willReturn($fileInfo);

        $this->beConstructedWith($pathalizer, $namespaceMapper);
        $this->createTestFor($subjectFqn)->shouldMatchTestCase(
            $subjectFqn,
            $testCaseFqn,
            __FILE__
        );
    }
}
