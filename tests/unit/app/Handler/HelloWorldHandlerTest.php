<?php

declare(strict_types=1);

namespace Tests\Unit\App\Handler;

use App\Handler\HelloWorldHandler;
use App\Message\HelloWorldMessage;
use PHPUnit\Framework\TestCase;

use Symfony\Component\Console\Output\OutputInterface;
use TechTest\BusinessLogic\HelloWorld\HelloWorldService;

final class HelloWorldHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function itHandlesADummyMessage(): void
    {
        $string = 'this is a test message';
        $dummyMessage = new HelloWorldMessage($string);
        /** @var OutputInterface $output */
        $output = $this->prophesize(OutputInterface::class);

        $dummyService = new HelloWorldService();
        $handler = new HelloWorldHandler($output->reveal(), $dummyService);

        $output->writeln($string)->shouldBeCalled();

        $handler($dummyMessage);
    }
}
