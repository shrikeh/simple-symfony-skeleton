<?php

declare(strict_types=1);

namespace spec\Tests\Utils\TestCaseBundle\Console\Command;

use PhpSpec\Exception\Example\MatcherException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\Utils\TestCaseBundle\Console\Command\CreateUnitTestCommand;
use Tests\Utils\TestCaseBundle\Message\CreateUnitTestMessage;

final class CreateUnitTestCommandSpec extends ObjectBehavior
{
    public function it_sends_a_message(
        MessageBusInterface $messageBus,
        InputInterface $input,
        OutputInterface $output
    ): void {
        $this->beConstructedWith($messageBus);

        $fqn = 'Thing\To\Test';

        $messageBus->dispatch(Argument::type(CreateUnitTestMessage::class))
            ->will(function(array $args) use ($fqn) {
                $msg = $args[0];
                if ($msg->getTestSubject() !== $fqn) {
                    throw new MatcherException('Fqn does not match!');
                }

                return new Envelope($msg);
            });
        $input->isInteractive()->shouldBeCalled();
        $input->hasArgument(Argument::any())->shouldBeCalled();
        $input->bind(Argument::type(InputDefinition::class))->shouldBeCalled();
        $input->validate()->shouldBeCalled();
        $input->getArgument(CreateUnitTestCommand::ARG_SUBJECT)->willReturn($fqn);

        $this->run($input, $output);
    }
}
