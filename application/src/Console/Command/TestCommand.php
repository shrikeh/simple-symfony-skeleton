<?php

declare(strict_types=1);

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TestCommand extends Command
{
    public const NAME = 'shrikeh:test';

    /**
     * TestCommand constructor.
     */
    public function __construct()
    {
        parent::__construct(static::NAME);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('we are here');
    }
}
