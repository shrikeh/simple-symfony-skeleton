<?php

declare(strict_types=1);

namespace Tests\Unit\App\Console\Command;

use App\Console\Command\HelloWorldCommand;
use App\Console\Command\Exception\CommandDispatchFailed;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use stdClass;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SentStamp;

final class HelloWorldCommandTest extends TestCase
{
    /** @var  ObjectProphecy */
    private ObjectProphecy $input;
    /** @var  ObjectProphecy */
    private ObjectProphecy $output;
    /** @var  ObjectProphecy */
    private ObjectProphecy $messageBus;
    /** @var  ObjectProphecy */
    private ObjectProphecy $logger;

    public function testItSendsADummyMessageToTheMessageBus(): void
    {
        $envelope = new Envelope(new stdClass());

        $this->messageBus->dispatch(Argument::type(Envelope::class))->willReturn($envelope);

        $testCommand = new HelloWorldCommand($this->messageBus->reveal(), $this->logger->reveal());
        $this->input->getOption(HelloWorldCommand::ARG_TEST_MESSAGE)->willReturn('Foo');
        $this->assertSame(0, $testCommand->run($this->input->reveal(), $this->output->reveal()));
    }

    public function testItCreatesEnvelopeWithStamp(): void
    {
        /** @var Envelope $envelope */
        $envelope = null;
        $this->messageBus->dispatch(Argument::type(Envelope::class))->will(
            function (array $args) use (&$envelope) {
                $envelope = $args[0];

                return $envelope;
            }
        );
        $testCommand = new HelloWorldCommand($this->messageBus->reveal(), $this->logger->reveal());

        $this->input->getOption(HelloWorldCommand::ARG_TEST_MESSAGE)->willReturn('Bar');

        $testCommand->run($this->input->reveal(), $this->output->reveal());

        $this->assertInstanceOf(SentStamp::class, $envelope->last(SentStamp::class));
    }

    /**
     * @test
     * @throws \Exception
     */
    public function itLogsSendingTheCommand(): void
    {
        /** @var Envelope $envelope */
        $envelope = null;
        $this->messageBus->dispatch(Argument::type(Envelope::class))->will(
            function (array $args) use (&$envelope) {
                $envelope = $args[0];

                return $envelope;
            }
        );
        $inputMsg = 'Baz';

        $testCommand = new HelloWorldCommand($this->messageBus->reveal(), $this->logger->reveal());
        $this->input->getOption(HelloWorldCommand::ARG_TEST_MESSAGE)->willReturn($inputMsg);
        $testCommand->run($this->input->reveal(), $this->output->reveal());

        $this->logger->debug(
            Argument::containingString('Message sent to command bus'),
            [HelloWorldCommand::LOG_CONTEXT]
        )->shouldHaveBeenCalled();

        $this->output->writeln(Argument::containingString($inputMsg))->shouldHaveBeenCalled();
    }

    public function testItThrowsACommandDispatchFailedExceptionIfTheMessageFailsToSend(): void
    {
        $this->messageBus->dispatch(Argument::type(Envelope::class))->willThrow(
            new TransportException('foo')
        );
        $this->input->getOption(HelloWorldCommand::ARG_TEST_MESSAGE)->willReturn('Bop');
        $testCommand = new HelloWorldCommand($this->messageBus->reveal(), $this->logger->reveal());
        $this->expectException(CommandDispatchFailed::class);
        $testCommand->run($this->input->reveal(), $this->output->reveal());
    }

    /**
     * @test
     */
    public function itConfiguresTheDefinition(): void
    {
        $testCommand = new HelloWorldCommand($this->messageBus->reveal(), $this->logger->reveal());

        $envelope = new Envelope(new stdClass());

        $this->messageBus->dispatch(Argument::any())->willReturn($envelope);
        $definition = $testCommand->getDefinition();
        $this->assertTrue($definition->hasOption(HelloWorldCommand::ARG_TEST_MESSAGE));
        $option = $definition->getOption(HelloWorldCommand::ARG_TEST_MESSAGE);
        $this->assertSame(HelloWorldCommand::DEFAULT_TEST_MESSAGE, $option->getDefault());
        $testCommand->run($this->input->reveal(), $this->output->reveal());
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        /** @var  MessageBusInterface $messageBus */
        $this->messageBus = $this->prophesize(MessageBusInterface::class);
        $this->input = $this->getInputProphet();

        $this->output = $this->prophesize(OutputInterface::class);
        $this->logger = $this->prophesize(LoggerInterface::class);
    }

    /**
     * @return ObjectProphecy
     */
    private function getInputProphet(): ObjectProphecy
    {
        $input = $this->prophesize(InputInterface::class);
        $input->bind(Argument::type(InputDefinition::class))->shouldBeCalled();
        $input->isInteractive()->willReturn(false);
        $input->hasArgument('command')->willReturn(false);
        $input->getOption(HelloWorldCommand::ARG_TEST_MESSAGE)->willReturn(HelloWorldCommand::DEFAULT_TEST_MESSAGE);
        $input->validate()->shouldBeCalled();

        return $input;
    }
}
