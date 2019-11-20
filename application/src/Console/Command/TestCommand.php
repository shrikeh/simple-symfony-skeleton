<?php

declare(strict_types=1);

namespace App\Console\Command;

use App\Message\TestMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class TestCommand extends Command implements ContainerAwareInterface
{
    public const NAME = 'shrikeh:test';
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

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
        $this->container->set(OutputInterface::class, $output);
        $this->messageBus->dispatch(new TestMessage('we are here'));
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }
}
