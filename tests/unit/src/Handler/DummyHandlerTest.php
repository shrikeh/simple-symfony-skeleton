<?php

declare(strict_types=1);

namespace Tests\Unit\App\Handler;

use App\Handler\DummyHandler;
use App\Message\DummyMessage;
use PHPUnit\Framework\TestCase;
use Shrikeh\TestSymfonyApp\Dummy\DummyService;
use Symfony\Component\Console\Output\OutputInterface;

final class DummyHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function itHandlesADummyMessage(): void
    {
        $string = 'this is a test message';
        $dummyMessage = new DummyMessage($string);
        /** @var OutputInterface $output */
        $output = $this->prophesize(OutputInterface::class);

        $dummyService = new DummyService();
        $handler = new DummyHandler($output->reveal(), $dummyService);

        $output->writeln($string)->shouldBeCalled();

        $handler($dummyMessage);
    }
}
