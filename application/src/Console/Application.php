<?php

declare(strict_types=1);

namespace App\Console;

use Symfony\Bundle\FrameworkBundle\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class Application extends SymfonyApplication
{
    /**
     * {@inheritDoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        $output = $output ?? $this->getOutputFromContainer();

        return parent::run($input, $output);
    }

    /**
     * @return OutputInterface
     */
    private function getOutputFromContainer(): OutputInterface
    {
        $kernel = $this->getKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        if ($container->has(OutputInterface::class)) {
            return $kernel->getContainer()->get(OutputInterface::class);
        }

        return new ConsoleOutput();
    }
}