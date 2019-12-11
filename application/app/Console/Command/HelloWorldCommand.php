<?php

declare(strict_types=1);

namespace App\Console\Command;

use App\Console\Command\Exception\CommandDispatchFailed;
use App\Message\HelloWorldMessage;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Throwable;

final class HelloWorldCommand extends Command
{
    public const NAME = 'shrikeh:helloworld';

    public const DEFAULT_TEST_MESSAGE = 'Hello World';

    public const ARG_TEST_MESSAGE = 'message';

    public const LOG_CONTEXT = 'console';
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * DummyCommand constructor.
     * @param MessageBusInterface $messageBus
     * @param LoggerInterface $logger
     */
    public function __construct(MessageBusInterface $messageBus, LoggerInterface $logger)
    {
        parent::__construct(static::NAME);

        $this->messageBus = $messageBus;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->addOption(
            self::ARG_TEST_MESSAGE,
            null,
            InputOption::VALUE_OPTIONAL,
            'What should I send to the message bus?',
            self::DEFAULT_TEST_MESSAGE
        );
    }

    /**
     * {@inheritDoc}
     * @throws CommandDispatchFailed If the message fails to dispatch
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $envelope = $this->getMessageEnvelope($input);
        try {
            $this->messageBus->dispatch($envelope);
        } catch (Exception $e) {
            throw CommandDispatchFailed::fromEnvelope($envelope, $e);
        }
        $this->logMessageSent($envelope);

        return 0;
    }

    /**
     * @param InputInterface $input
     * @return Envelope
     */
    private function getMessageEnvelope(InputInterface $input): Envelope
    {
        $stamp = new SentStamp(
            __CLASS__,
            sprintf('console:%s', $this->getName())
        );
        return new Envelope(
            new HelloWorldMessage($input->getOption(self::ARG_TEST_MESSAGE)),
            [$stamp]
        );
    }

    /**
     * @param Envelope $envelope
     */
    private function logMessageSent(Envelope $envelope): void
    {
        $logMsg = sprintf(
            'Message sent to command bus: (%s)',
            json_encode($envelope->getMessage())
        );
        try {
            $this->logger->debug(
                $logMsg,
                [static::LOG_CONTEXT]
            );
        } catch (Throwable $e) {
        }
    }
}
