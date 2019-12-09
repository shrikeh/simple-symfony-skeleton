<?php
declare(strict_types=1);

namespace Tests\Utils\UnitTestBundle\Handler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Tests\Utils\UnitTest\Service\TestCaseGenerator;
use Tests\Utils\UnitTestBundle\Message\CreateUnitTestMessage;
use Tests\Utils\UnitTestBundle\TestCaseRenderer\TestCaseRendererInterface;

final class CreateUnitTestHandler implements MessageHandlerInterface
{
    /** @var TestCaseGenerator */
    private TestCaseGenerator $testCaseGenerator;
    /**
     * @var TestCaseRendererInterface
     */
    private $testCaseRenderer;

    /**
     * CreateUnitTestHandler constructor.
     * @param TestCaseGenerator $testCaseGenerator
     * @param TestCaseRendererInterface $testCaseRenderer
     */
    public function __construct(
        TestCaseGenerator $testCaseGenerator,
        TestCaseRendererInterface $testCaseRenderer
    ) {
        $this->testCaseGenerator = $testCaseGenerator;
        $this->testCaseRenderer = $testCaseRenderer;
    }

    /**
     * @param CreateUnitTestMessage $message
     *
     */
    public function __invoke(CreateUnitTestMessage $message)
    {
        $testCase = $this->testCaseGenerator->createTestFor($message->getTestSubject());
        $this->testCaseRenderer->render($testCase);
    }
}