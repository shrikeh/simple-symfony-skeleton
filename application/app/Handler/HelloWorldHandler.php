<?php

declare(strict_types=1);

namespace App\Handler;

use App\Message\HelloWorldMessage;
use Shrikeh\TestSymfonyApp\Message\DummyMessage;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use TechTest\BusinessLogic\HelloWorld\HelloWorldService;

final class HelloWorldHandler implements MessageHandlerInterface
{
    /** @var OutputInterface  */
    private OutputInterface $output;
    /**
     * @var HelloWorldService
     */
    private HelloWorldService $dummyService;

    /**
     * HelloWorldHandler constructor.
     * @param OutputInterface $output
     * @param HelloWorldService $dummyService
     */
    public function __construct(OutputInterface $output, HelloWorldService $dummyService)
    {
        $this->output = $output;
        $this->dummyService = $dummyService;
    }

    /**
     * @param HelloWorldMessage $dummyMessage
     */
    public function __invoke(HelloWorldMessage $dummyMessage): void
    {
        $result = $this->dummyService->dummyMessage($dummyMessage->getDummyMessage());
        $this->output->writeln($result);
    }
}
