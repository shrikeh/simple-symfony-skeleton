<?php

declare(strict_types=1);

namespace App\Console;

use Symfony\Bundle\FrameworkBundle\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class Application extends SymfonyApplication
{
    /** @var ContainerInterface */
    private ContainerInterface $container;

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->getKernel()->isDebug();
    }

    /**
     * {@inheritDoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        $input = $input ?? $this->getInputFromContainer();
        $output = $output ?? $this->getOutputFromContainer();

        return parent::run($input, $output);
    }

    /**
     * @return InputInterface
     */
    private function getInputFromContainer(): InputInterface
    {
        $container = $this->getKernelContainer();

        if ($container->has(InputInterface::class)) {
            return $container->get(InputInterface::class);
        }

        return new ArgvInput();
    }

    /**
     * @return OutputInterface
     */
    private function getOutputFromContainer(): OutputInterface
    {
        $container = $this->getKernelContainer();

        if ($container->has(OutputInterface::class)) {
            return $container->get(OutputInterface::class);
        }

        return new ConsoleOutput();
    }

    /**
     * @return ContainerInterface
     */
    private function getKernelContainer(): ContainerInterface
    {
        if (!isset($this->container)) {
            $kernel = $this->getKernel();
            $kernel->boot();

            $this->container = $kernel->getContainer();
        }

        return $this->container;
    }
}
