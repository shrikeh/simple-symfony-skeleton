<?php

declare(strict_types=1);

namespace Tests\Unit\TestSymfonyApp\Kernel\Environment;

use Shrikeh\TestSymfonyApp\Kernel\Environment\Environment;
use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ServerBag;

final class EnvironmentTest extends TestCase
{
    private const SHELL_VERBOSITY = Environment::KEY_SHELL_VERBOSITY;

    /** @var array */
    private array $originalEnv;

    /** @var array */
    private array $originalServer;

    /**
     * @test
     */
    public function itSetsTheShellVerbosity(): void
    {
        unset($_ENV[self::SHELL_VERBOSITY], $_SERVER[self::SHELL_VERBOSITY]);

        $environment = Environment::create(Environment::ENV_TEST, null, true);
        $environment->setDebugShellVerbosity();

        $this->assertSame(Environment::SHELL_VERBOSITY_LEVEL, $_SERVER[self::SHELL_VERBOSITY]);
        $this->assertSame(Environment::SHELL_VERBOSITY_LEVEL, $_ENV[self::SHELL_VERBOSITY]);
        $this->assertEquals(
            getenv(self::SHELL_VERBOSITY, true),
            Environment::SHELL_VERBOSITY_LEVEL
        );

        $verbosity = 2;
        $_ENV[self::SHELL_VERBOSITY] = $verbosity;
        $_SERVER[self::SHELL_VERBOSITY] = $verbosity;

        $environment->setDebugShellVerbosity();

        $this->assertSame($verbosity, $_ENV[self::SHELL_VERBOSITY]);
        $this->assertSame($verbosity, $_SERVER[self::SHELL_VERBOSITY]);
    }

    /**
     * @test
     */
    public function itDoesNotSetShellVerbosityIfItIsAlreadySet(): void
    {
        $environment = Environment::create(Environment::ENV_TEST, null, true);
        $verbosity = 2;
        $_ENV[self::SHELL_VERBOSITY] = $verbosity;
        unset($_SERVER[self::SHELL_VERBOSITY]);

        $environment->setDebugShellVerbosity();

        $this->assertSame($verbosity, $_ENV[self::SHELL_VERBOSITY]);
        $this->assertArrayNotHasKey(self::SHELL_VERBOSITY, $_SERVER);

        unset($_ENV[self::SHELL_VERBOSITY]);
        $_SERVER[self::SHELL_VERBOSITY] = $verbosity;

        $environment->setDebugShellVerbosity();

        $this->assertSame($verbosity, $_SERVER[self::SHELL_VERBOSITY]);
        $this->assertArrayNotHasKey(self::SHELL_VERBOSITY, $_ENV);
    }

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

    /**
     * Store the existing $_ENV and $_SERVER vars in between test cases.
     */
    protected function setUp(): void
    {
        $this->originalEnv = $_ENV;
        $this->originalServer = $_SERVER;
    }

    /**
     * Restore the $_ENV and $_SERVER vars back to their original state.
     */
    protected function tearDown(): void
    {
        $_ENV = $this->originalEnv;
        $_SERVER = $this->originalServer;
    }
}
