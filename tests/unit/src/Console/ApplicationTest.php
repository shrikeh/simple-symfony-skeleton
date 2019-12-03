<?php

declare(strict_types=1);

namespace Tests\Unit\App\Console;

use App\Console\Application;
use App\Kernel\Environment\EnvironmentInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Tests\Mock\Console\Command\NullCommand;

final class ApplicationTest extends TestCase
{
    private const COMMAND_NAME = 'test_command';

    /**
     * @test
     * @throws \Exception
     */
    public function itUsesTheInjectedInputAndOutput(): void
    {
        $container = $this->getContainerDouble();
        $kernel = $this->getKernelDouble($container);
        $kernel->boot()->shouldBeCalledTimes(1);
        $output = new NullOutput();
        $application = $this->getApplication($kernel->reveal());

        $input = $this->getInput();
        $command = new NullCommand(static::COMMAND_NAME);

        $application->add($command);
        $application->run($input, $output);

        $this->assertSame($input, $command->getInput());
        $this->assertSame($output, $command->getOutput());
    }


    /**
     * @test
     */
    public function itUsesTheOutputFromTheContainerIfNoneIsInjected(): void
    {
        $output = $this->prophesize(OutputInterface::class)->reveal();
        $container = $this->getContainerDouble();
        $container->has(OutputInterface::class)->willReturn(true);


        $container->get(OutputInterface::class)->will(function () use ($output) {
            return $output;
        });

        $kernel = $this->getKernelDouble($container);
        $kernel->boot()->shouldBeCalledTimes(2);

        $application = $this->getApplication($kernel->reveal());

        $command = new NullCommand(static::COMMAND_NAME);
        $application->add($command);
        $application->run($this->getInput());


        $this->assertSame(
            $output,
            $command->getOutput()
        );
    }

    public function testItReturnsTheKernelDebugMode(): void
    {
        $container = $this->getContainerDouble();
        $kernel = $this->getKernelDouble($container);

        $kernel->isDebug()->willReturn(false, true);
        $kernel->boot()->shouldNotBeCalled();

        $application = $this->getApplication($kernel->reveal());

        $this->assertSame(false, $application->isDebug());
        $this->assertSame(true, $application->isDebug());
    }


    /**
     * @param KernelInterface $kernel
     * @return Application
     */
    private function getApplication(KernelInterface $kernel): Application
    {
        $application = new Application($kernel);
        $application->setCatchExceptions(false);
        $application->setAutoExit(false);

        return $application;
    }

    /**
     * @return StringInput
     */
    private function getInput(): StringInput
    {
        $input = new StringInput(static::COMMAND_NAME);
        $input->setInteractive(false);

        return $input;
    }

    /**
     * @param $container
     * @return ObjectProphecy
     */
    private function getKernelDouble($container): ObjectProphecy
    {
        $kernel = $this->prophesize(KernelInterface::class);
        $kernel->getEnvironment()->willReturn(EnvironmentInterface::ENV_TEST);
        $kernel->getBundles()->willReturn([]);

        $kernel->boot()->will(function () use ($kernel, $container) {
            $kernel->getContainer()->willReturn($container->reveal());
        });

        return $kernel;
    }

    /**
     * @return ObjectProphecy
     */
    private function getContainerDouble(): ObjectProphecy
    {
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $container = $this->prophesize(ContainerInterface::class);

        $container->has('console.command_loader')->willReturn(false);
        $container->hasParameter('console.command.ids')->willReturn(false);
        $container->get('event_dispatcher')->will(function () use ($eventDispatcher) {
            return $eventDispatcher->reveal();
        });

        return $container;
    }
}
