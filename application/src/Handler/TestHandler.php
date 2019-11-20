<?php

declare(strict_types=1);

namespace App\Handler;

use App\Message\TestMessage;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class TestHandler implements MessageHandlerInterface
{
    /** @var OutputInterface  */
    private OutputInterface $output;

    /**
     * TestHandler constructor.
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param TestMessage $testMessage
     */
    public function __invoke(TestMessage $testMessage): void
    {
        $this->output->writeln($testMessage->getTestMessage());
    }
}