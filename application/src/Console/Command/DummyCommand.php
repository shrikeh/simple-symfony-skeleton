<?php

declare(strict_types=1);

namespace App\Console\Command;

use App\Console\Command\Exception\CommandDispatchFailed;
use App\Message\DummyMessage;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Throwable;

final class DummyCommand extends Command
{
    public const NAME = 'shrikeh:dummy';

    public const DEFAULT_TEST_MESSAGE = 'we are here';

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
     * {@inheritDoc}
     * @throws CommandDispatchFailed If the message fails to dispatch
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $stamp = new SentStamp(__CLASS__, sprintf('console:%s', $this->getName()));
        $envelope = new Envelope(
            new DummyMessage(static::DEFAULT_TEST_MESSAGE),
            [$stamp]
        );
        try {
            $this->messageBus->dispatch($envelope);
        } catch (Exception $e) {
            throw CommandDispatchFailed::fromEnvelope($envelope, $e);
        }
        $this->logMessageSent($envelope);
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