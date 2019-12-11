<?php

declare(strict_types=1);

namespace Tests\Unit\App\Console\Command\Exception;

use App\Console\Command\Exception\CommandDispatchFailed;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Teapot\StatusCode;

final class CommandDispatchFailedTest extends TestCase
{
    public function testItCreatesACommandDispatchFailed(): void
    {
        $previousException = new Exception();

        $envelope = new Envelope(new stdClass());

        $commandException = CommandDispatchFailed::fromEnvelope($envelope, $previousException);

        $this->assertSame($envelope, $commandException->getEnvelope());
        $this->assertSame(CommandDispatchFailed::MSG, $commandException->getMessage());
        $this->assertSame(StatusCode::INTERNAL_SERVER_ERROR, $commandException->getCode());
    }
}
