<?php

declare(strict_types=1);

namespace Tests\Unit\TestSymfonyApp\Console;

use Shrikeh\TestSymfonyApp\Console\Exception\HandleMethodNotImplemented;
use Shrikeh\TestSymfonyApp\Console\Kernel;
use Shrikeh\TestSymfonyApp\Booter\BooterInterface;
use Shrikeh\TestSymfonyApp\Kernel\ConfigurationLoader\ConfigurationLoaderInterface;
use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ServerBag;

final class KernelTest extends TestCase
{
    /**
     * @test
     */
    public function itDoesNotImplementHttpExclusiveKernelMethods(): void
    {
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $this->expectException(HandleMethodNotImplemented::class);

        $kernel->handle(new Request());
    }

    /**
     * @test
     */
    public function itReturnsTheCorrectBootedState(): void
    {
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

        $booter->isBooted()->willReturn(false);

        $booter->boot(Argument::type(Kernel::class))->will(function () use ($booter) {
            $booter->isBooted()->willReturn(true);
        });

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $this->assertFalse($kernel->isBooted());
        $kernel->boot();
        $this->assertTrue($kernel->isBooted());
        $booter->boot($kernel)->shouldHaveBeenCalled();
    }

    /**
     * @test
     */
    public function itRegistersConfigurationWithTheConfigLoader(): void
    {
        /** @var EnvironmentInterface $environment */
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

        $loader = $this->prophesize(LoaderInterface::class);
        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $configurationLoader->loadConfig($loader)->shouldBeCalled();

        $kernel->registerContainerConfiguration($loader->reveal());
    }

    /**
     * @test
     */
    public function itUsesTheCacheDirFromTheServerBag(): void
    {
        $cacheDir = 'foo';
        $serverBag = new ServerBag([Kernel::SERVER_CACHE_DIR => $cacheDir]);

        /** @var EnvironmentInterface $environment */
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

        $environment->getServerBag()->willReturn($serverBag);

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $this->assertSame($cacheDir, $kernel->getCacheDir());
    }

    /**
     * @test
     */
    public function itUsesTheLogDirFromTheServerBag(): void
    {
        $logDir = 'bar';
        $serverBag = new ServerBag([Kernel::SERVER_LOG_DIR => $logDir]);
        /** @var EnvironmentInterface $environment */
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

        $environment->getServerBag()->willReturn($serverBag);

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $this->assertSame($logDir, $kernel->getLogDir());
    }

    /**
     * @test
     */
    public function itReturnsTheProjectDir(): void
    {
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $this->assertSame(PROJECT_DIR, $kernel->getProjectDir());
    }

    /**
     * @test
     */
    public function itUsesTheInjectedDebugMode(): void
    {
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

        $environment->isDebug()->willReturn(true, false);

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $this->assertTrue($kernel->isDebug());
        $this->assertFalse($kernel->isDebug());
    }

    /**
     * @test
     */
    public function itDoesNotShutdownTheBooterIfItHasNotBooted(): void
    {
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

        $booter->isBooted()->willReturn(false);
        $booter->shutdown()->shouldNotBeCalled();

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $kernel->shutdown();
    }

    /**
     * @test
     */
    public function itShutsDownTheBooterIfItHasBooted(): void
    {
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

        $booter->isBooted()->willReturn(true);
        $booter->shutdown()->shouldBeCalled();

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $kernel->shutdown();
    }

    /**
     * @test
     */
    public function itLoadsTheContainerFromTheBooter(): void
    {
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);
        $container = $this->prophesize(ContainerInterface::class);

        /** var BooterInterface */
        $containerProphet = $container->reveal();
        $booter->getContainer()->willReturn($containerProphet);

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $booter->boot($kernel)->shouldBeCalled();
        $kernel->boot();


        $this->assertSame($kernel->getContainer(), $containerProphet);
    }

    /**
     * @test
     */
    public function itUsesTheEnvironmentName(): void
    {
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

        $environment->getName()->willReturn(EnvironmentInterface::ENV_TEST);

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $this->assertSame(EnvironmentInterface::ENV_TEST, $kernel->getEnvironment());
    }
}
