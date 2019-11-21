<?php

declare(strict_types=1);

namespace App\Handler;

use App\Message\DummyMessage;
use Shrikeh\TestSymfonyApp\Dummy\DummyService;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DummyHandler implements MessageHandlerInterface
{
    /** @var OutputInterface  */
    private OutputInterface $output;
    /**
     * @var DummyService
     */
    private DummyService $dummyService;

    /**
     * DummyHandler constructor.
     * @param OutputInterface $output
     * @param DummyService $dummyService
     */
    public function __construct(OutputInterface $output, DummyService $dummyService)
    {
        $this->output = $output;
        $this->dummyService = $dummyService;
    }

    /**
     * @param DummyMessage $dummyMessage
     */
    public function __invoke(DummyMessage $dummyMessage): void
    {
        $result = $this->dummyService->dummyMessage($dummyMessage->getDummyMessage());

        $this->output->writeln($result);
    }
}
