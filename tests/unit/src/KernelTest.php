<?php

declare(strict_types=1);

namespace Tests\Unit\App;

use App\Kernel;
use App\Kernel\Exception\UnrecognisedEnvironment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\ServerBag;

final class KernelTest extends KernelTestCase
{
    public function testItReturnsTheCorrectBootedState(): void
    {
        $kernel = new Kernel(new ServerBag(), Kernel::ENV_TEST, false);
        $this->assertFalse($kernel->isBooted());
        $kernel->boot();
        $this->assertTrue($kernel->isBooted());
    }


    public function testItThrowsAnExceptionIfTheEnvironmentIsUnrecognised(): void
    {
        $this->expectException(UnrecognisedEnvironment::class);
        new Kernel(new ServerBag(), 'foo', false);
    }

    public function testItUsesTheCacheDirFromTheServer(): void
    {
        $cacheDir = 'foo';
        $serverBag = new ServerBag([Kernel::SERVER_CACHE_DIR => $cacheDir]);
        $kernel = new Kernel($serverBag, Kernel::ENV_DEV, false);

        $this->assertSame($cacheDir, $kernel->getCacheDir());
    }

    public function testItUsesTheLogDirFromTheServer(): void
    {
        $logDir = 'bar';
        $serverBag = new ServerBag([Kernel::SERVER_LOG_DIR => $logDir]);
        $kernel = new Kernel($serverBag, Kernel::ENV_DEV, false);

        $this->assertSame($logDir, $kernel->getLogDir());
    }

    public function testItUsesTheInjectedDebugMode(): void
    {
        $serverBag = new ServerBag([
            Kernel::SERVER_APP_DEBUG => false,
            Kernel::SERVER_APP_ENV => Kernel::ENV_TEST,
        ]);
        $kernel = Kernel::fromServerBag($serverBag, true);

        $this->assertTrue($kernel->isDebug());

        $serverBag = new ServerBag([
            Kernel::SERVER_APP_DEBUG => true,
            Kernel::SERVER_APP_ENV => Kernel::ENV_TEST,
        ]);
        $kernel = Kernel::fromServerBag($serverBag, false);

        $this->assertFalse($kernel->isDebug());
    }

    public function testItUsesTheServerDebugVarIfNoDebugModeIsExplicitlySet(): void
    {
        $serverBag = new ServerBag([
            Kernel::SERVER_APP_DEBUG => false,
            Kernel::SERVER_APP_ENV => Kernel::ENV_TEST,
        ]);

        $kernel = Kernel::fromServerBag($serverBag);
        $this->assertFalse($kernel->isDebug());

        $serverBag = new ServerBag([
            Kernel::SERVER_APP_DEBUG => true,
            Kernel::SERVER_APP_ENV => Kernel::ENV_TEST,
        ]);
        $kernel = Kernel::fromServerBag($serverBag);
        $this->assertTrue($kernel->isDebug());
    }

    public function testItConfiguresTheContainer(): void
    {
        $serverBag = new ServerBag([
            Kernel::SERVER_APP_DEBUG => false,
            Kernel::SERVER_APP_ENV => Kernel::ENV_TEST,
        ]);

        $kernel = Kernel::fromServerBag($serverBag);
        $kernel->boot();

        $this->assertTrue($kernel->getContainer()->getParameter('container.dumper.inline_class_loader'));
    }
}
