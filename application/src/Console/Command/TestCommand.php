<?php

declare(strict_types=1);

namespace App\Console\Command;

use App\Message\TestMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class TestCommand extends Command
{
    public const NAME = 'shrikeh:test';
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * TestCommand constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        parent::__construct(static::NAME);

        $this->messageBus = $messageBus;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->messageBus->dispatch(new TestMessage('we are here'));
    }
}
