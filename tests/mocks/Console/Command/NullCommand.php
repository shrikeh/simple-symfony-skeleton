<?php

declare(strict_types=1);

namespace Tests\Mock\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class NullCommand extends Command
{
    /** @var InputInterface */
    private ?InputInterface $input = null;

    /** @var OutputInterface */
    private ?OutputInterface $output = null;

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @return InputInterface
     */
    public function getInput(): ?InputInterface
    {
        return $this->input;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput(): ?OutputInterface
    {
        return $this->output;
    }
}
