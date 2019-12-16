<?php

declare(strict_types=1);

namespace spec\Tests\Utils\TestCaseBundle\Handler;

use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\Service\NamespaceMapper\NamespaceMapper;
use Tests\Utils\UnitTest\Service\NamespaceMapper\NamespaceMapperInterface;
use Tests\Utils\UnitTest\Service\Pathalizer\PathalizerInterface;
use Tests\Utils\UnitTest\Service\TestCaseGenerator;
use Tests\Utils\UnitTest\TestCase;
use Tests\Utils\TestCaseBundle\Handler\CreateUnitTestHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tests\Utils\TestCaseBundle\Message\CreateUnitTestMessage;
use Tests\Utils\TestCaseBundle\TestCaseRenderer\TestCaseRendererInterface;

final class CreateUnitTestHandlerSpec extends ObjectBehavior
{
    public function let(
        PathalizerInterface $pathalizer,
        TestCaseRendererInterface $testCaseRenderer,
        Filesystem $filesystem,
        NamespaceMapperInterface $namespaceMapper
    ): void {
        $testCaseGenerator = new TestCaseGenerator(
            $pathalizer->getWrappedObject(),
            $namespaceMapper->getWrappedObject()
        );
        $this->beConstructedWith(
            $testCaseGenerator,
            $testCaseRenderer,
            $filesystem
        );
    }

    public function it_creates_a_test_case(
        PathalizerInterface $pathalizer,
        TestCaseRendererInterface $testCaseRenderer,
        Filesystem $filesystem,
        NamespaceMapperInterface $namespaceMapper
    ): void {
        $subjectFqn = 'Some\Subject\ToTest';
        $testCaseFqn = ClassNamespace::fromNamespaceString('Some\New\TestCase');

        $message = new CreateUnitTestMessage($subjectFqn);
        $fileInfo = new SplFileInfo(__FILE__);

        $namespaceMapper->getTestCaseFqnFor(Argument::type(ClassNamespace::class))
            ->willReturn($testCaseFqn);

        $pathalizer->getUnitTestPathFor($testCaseFqn)->willReturn($fileInfo);

        $content = 'foo';

        $testCaseRenderer->render(Argument::type(TestCase::class))->willReturn($content);

        $filesystem->dumpFile(__FILE__, $content)->shouldBeCalled();

        $this($message);
    }
}
