<?php

declare(strict_types=1);

namespace Tests\Unit\App\Kernel\Booter;

use App\Kernel\Booter\Booter;
use App\Kernel\Booter\BundleLoader\BundlerLoaderInterface;
use App\Kernel\Booter\ContainerLoader\ContainerLoaderInterface;
use App\Kernel\Environment\Environment;
use App\Kernel\Environment\EnvironmentInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class BooterTest extends TestCase
{
    /**
     * @test
     */
    public function itDoesNotShutsdownIfItHasNotBeenBooted(): void
    {
        $bundlerLoader = $this->prophesize(BundlerLoaderInterface::class);
        $containerLoader = $this->prophesize(ContainerLoaderInterface::class);
        $environment = Environment::create(Environment::ENV_TEST);

        $testBundle = $this->prophesize(BundleInterface::class);

        $testBundle->shutdown()->shouldNotBeCalled();
        $testBundle->setContainer(null)->shouldNotBeCalled();

        $bundlerLoader->getBundles()->willReturn([$testBundle->reveal()]);

        $booter = new Booter(
            $bundlerLoader->reveal(),
            $containerLoader->reveal(),
            $environment
        );

        $booter->shutdown();
    }

    /**
     * @test
     */
    public function itShutsdownBundlesIfItHasBeenBooted(): void
    {
        $kernel = $this->prophesize(KernelInterface::class);
        $bundlerLoader = $this->prophesize(BundlerLoaderInterface::class);
        $containerLoader = $this->prophesize(ContainerLoaderInterface::class);
        $container = $this->prophesize(ContainerInterface::class);
        $containerLoader->loadContainer($kernel)->willReturn($container->reveal());
        $environment = Environment::create(Environment::ENV_TEST);
        $testBundle = $this->prophesize(BundleInterface::class);

        $bundlerLoader->getBundles()->willReturn([$testBundle->reveal()]);

        $testBundle->boot()->shouldBeCalled();
        $testBundle->setContainer($container)->shouldBeCalled();

        $booter = new Booter(
            $bundlerLoader->reveal(),
            $containerLoader->reveal(),
            $environment
        );

        $booter->boot($kernel->reveal());
        $this->assertTrue($booter->isBooted());

        $testBundle->setContainer(null)->shouldBeCalled();
        $testBundle->shutdown()->shouldBeCalled();

        $booter->shutdown();
        $this->assertFalse($booter->isBooted());
    }

    /**
     * @test
     */
    public function itSetsTheEnvironmentShellVerbosity(): void
    {
        $kernel = $this->prophesize(KernelInterface::class);
        $bundlerLoader = $this->prophesize(BundlerLoaderInterface::class);
        $containerLoader = $this->prophesize(ContainerLoaderInterface::class);
        $container = $this->prophesize(ContainerInterface::class);
        $containerLoader->loadContainer($kernel)->willReturn($container->reveal());

        $environment = $this->prophesize(EnvironmentInterface::class);

        $bundlerLoader->getBundles()->willReturn([]);

        $booter = new Booter(
            $bundlerLoader->reveal(),
            $containerLoader->reveal(),
            $environment->reveal()
        );

        $booter->boot($kernel->reveal());

        $environment->setDebugShellVerbosity()->shouldHaveBeenCalled();
    }
}