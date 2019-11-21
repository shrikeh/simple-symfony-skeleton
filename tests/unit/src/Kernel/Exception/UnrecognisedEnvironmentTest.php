<?php

declare(strict_types=1);

namespace Tests\Unit\App\Kernel\Exception;

use App\Kernel\Exception\UnrecognisedEnvironment;
use PHPUnit\Framework\TestCase;

final class UnrecognisedEnvironmentTest extends TestCase
{
    public function testItContainsTheEnvironmentInTheMsg(): void
    {
        $badEnv = 'foo';
        $exception = UnrecognisedEnvironment::create($badEnv);

        $this->assertStringContainsString($badEnv, $exception->getMessage());
    }
}
