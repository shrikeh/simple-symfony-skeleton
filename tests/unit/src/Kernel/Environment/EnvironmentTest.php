<?php

declare(strict_types=1);

namespace Tests\Unit\App\Kernel\Environment;

use App\Kernel\Environment\Environment;
use App\Kernel\Environment\EnvironmentInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ServerBag;

final class EnvironmentTest extends TestCase
{
    /**
     * @test
     */
    public function itUsesTheDebugMode(): void
    {
        $environment = Environment::create(
            EnvironmentInterface::ENV_TEST,
        );

        $this->assertFalse($environment->isDebug());

        $environment = Environment::create(
            EnvironmentInterface::ENV_TEST,
            null,
            true
            );

        $this->assertTrue($environment->isDebug());
    }

    /**
     * @test
     */
    public function itIsAString(): void
    {
        $environment = Environment::create(
            EnvironmentInterface::ENV_TEST
        );

        $this->assertSame(EnvironmentInterface::ENV_TEST, (string) $environment);
    }

    /**
     * @test
     */
    public function itReturnsTheEnvironmentName(): void
    {
        $environment = Environment::create(
            EnvironmentInterface::ENV_TEST
        );

        $this->assertSame(EnvironmentInterface::ENV_TEST, $environment->getName());
    }

    /**
     * @test
     */
    public function itReturnsTheServerBag(): void
    {
        $serverBag = new ServerBag();
        $environment = new Environment($serverBag, EnvironmentInterface::ENV_TEST, true);
        $this->assertSame($serverBag, $environment->getServerBag());
    }
}