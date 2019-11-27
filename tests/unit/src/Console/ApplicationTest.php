<?php

declare(strict_types=1);

namespace Tests\Unit\App\Console;

use App\Console\Application;
use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Tests\Mock\Console\Command\NullCommand;

final class ApplicationTest extends KernelTestCase
{
    private const COMMAND_NAME = 'test_command';

    public function testItUsesTheInjectedOutput(): void
    {
        $output = new NullOutput();

        $application = $this->getApplication(static::createKernel());
        $input = $this->getInput();
        $command = new NullCommand(static::COMMAND_NAME);

        $application->add($command);
        $application->run($input, $output);

        $this->assertSame($input, $command->getInput());
        $this->assertSame($output, $command->getOutput());
    }

    /**
     * @covers \App\Kernel::boot()
     */
    public function testItUsesTheOutputFromTheContainerIfNoneIsInjected(): void
    {
        $kernel = static::createKernel();

        $this->assertFalse($kernel->isBooted());

        $application = $this->getApplication($kernel);

        $command = new NullCommand(static::COMMAND_NAME);
        $application->add($command);
        $application->run($this->getInput());

        $this->assertTrue($kernel->isBooted());

        $this->assertSame(
            $kernel->getContainer()->get(OutputInterface::class),
            $command->getOutput()
        );
    }

    /**
     * @param array $options
     * @return Kernel
     */
    protected static function createKernel(array $options = []): Kernel
    {
        return Kernel::fromServerBag(new ServerBag($_SERVER));
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
}
