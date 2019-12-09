<?php

declare(strict_types=1);

namespace Tests\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\Utils\UnitTestBundle\Message\CreateUnitTestMessage;

final class CreateUnitTestCommand extends Command
{
    public const NAME = 'testcase:create';
    public const ARG_SUBJECT = 'subject';

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * CreateUnitTestCommand constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        parent::__construct(self::NAME);
        $this->messageBus = $messageBus;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
       $this->addArgument(
           self::ARG_SUBJECT,
           InputArgument::REQUIRED,
           'What class is it you want to test?'
       );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->messageBus->dispatch(
            $this->createMessage($input)
        );
    }

    /**
     * @param InputInterface $input
     * @return CreateUnitTestMessage
     */
    private function createMessage(InputInterface $input): CreateUnitTestMessage
    {
        return new CreateUnitTestMessage($input->getArgument(self::ARG_SUBJECT));
    }
}
