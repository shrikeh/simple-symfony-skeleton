<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Command;

use App\Message\TestMessage;
use App\Console\Command\TestCommand;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class TestCommandTest extends TestCase
{
    public function testItSendsATestMessageToTheMessageBus(): void
    {
        /** @var  MessageBusInterface $messageBus */
        $messageBus = $this->prophesize(MessageBusInterface::class);

        $testCommand = new TestCommand($messageBus->reveal());

        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);

        $envelope = new Envelope(new stdClass());

        $messageBus->dispatch(Argument::type(TestMessage::class))->willReturn($envelope);

        $this->assertSame(0, $testCommand->run($input->reveal(), $output->reveal()));
    }
}
