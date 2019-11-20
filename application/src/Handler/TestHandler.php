<?php

declare(strict_types=1);

namespace App\Handler;

use App\Message\TestMessage;
use Shrikeh\TestSymfonyApp\Test\TestService;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class TestHandler implements MessageHandlerInterface
{
    /** @var OutputInterface  */
    private OutputInterface $output;
    /**
     * @var TestService
     */
    private TestService $testService;

    /**
     * TestHandler constructor.
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output, TestService $testService)
    {
        $this->output = $output;
        $this->testService = $testService;
    }

    /**
     * @param TestMessage $testMessage
     */
    public function __invoke(TestMessage $testMessage): void
    {
        $result = $this->testService->testMessage($testMessage->getTestMessage());

        $this->output->writeln($result);
    }
}