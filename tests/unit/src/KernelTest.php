<?php

declare(strict_types=1);

namespace Tests\Unit\App;

use App\Kernel;

use App\Kernel\Booter\BooterInterface;
use App\Kernel\ConfigurationLoader\ConfigurationLoaderInterface;
use App\Kernel\Environment\EnvironmentInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\ServerBag;

final class KernelTest extends TestCase
{
    public function testItReturnsTheCorrectBootedState(): void
    {
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);

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

    public function testItUsesTheCacheDirFromTheServer(): void
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

    public function testItUsesTheLogDirFromTheServer(): void
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

    public function testItUsesTheInjectedDebugMode(): void
    {
        $environment = $this->prophesize(EnvironmentInterface::class);
        $booter = $this->prophesize(BooterInterface::class);
        $configurationLoader = $this->prophesize(ConfigurationLoaderInterface::class);
        /** @var EnvironmentInterface $environment */
        $environment->isDebug()->willReturn(true, false);

        $kernel = new Kernel(
            $environment->reveal(),
            $booter->reveal(),
            $configurationLoader->reveal()
        );

        $this->assertTrue($kernel->isDebug());
        $this->assertFalse($kernel->isDebug());
    }

    public function testItLoadsTheContainerFromTheBooter(): void
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
}
