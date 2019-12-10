<?php

declare(strict_types=1);

namespace Tests\Utils\TestCaseBundle\Handler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Tests\Utils\UnitTest\Service\TestCaseGenerator;
use Tests\Utils\TestCaseBundle\Message\CreateUnitTestMessage;
use Tests\Utils\TestCaseBundle\TestCaseRenderer\TestCaseRendererInterface;

final class CreateUnitTestHandler implements MessageHandlerInterface
{
    /** @var TestCaseGenerator */
    private TestCaseGenerator $testCaseGenerator;
    /**
     * @var TestCaseRendererInterface
     */
    private TestCaseRendererInterface $testCaseRenderer;
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * CreateUnitTestHandler constructor.
     * @param TestCaseGenerator $testCaseGenerator
     * @param TestCaseRendererInterface $testCaseRenderer
     * @param Filesystem $filesystem
     */
    public function __construct(
        TestCaseGenerator $testCaseGenerator,
        TestCaseRendererInterface $testCaseRenderer,
        Filesystem $filesystem
    ) {
        $this->testCaseGenerator = $testCaseGenerator;
        $this->testCaseRenderer = $testCaseRenderer;
        $this->filesystem = $filesystem;
    }

    /**
     * @param CreateUnitTestMessage $message
     *
     */
    public function __invoke(CreateUnitTestMessage $message)
    {
        $testCase = $this->testCaseGenerator->createTestFor($message->getTestSubject());
        $testCasePath = $testCase->getFileInfo();

        $this->filesystem->dumpFile(
            $testCasePath->getPathname(),
            $this->testCaseRenderer->render($testCase)
        );
    }
}
