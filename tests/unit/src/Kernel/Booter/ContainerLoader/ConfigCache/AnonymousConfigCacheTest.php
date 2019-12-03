<?php

declare(strict_types=1);

use App\Kernel\Booter\ContainerLoader\ConfigCache\AnonymousConfigCache;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

final class AnonymousConfigCacheTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsTrueIfItIsFresh(): void
    {
        $lock = $this->prophesize(SplFileObject::class);
        $filesystem = new Filesystem();

        $lock->isFile()->willReturn(true);
        $lock->flock(LOCK_UN)->shouldBeCalled();

        $configCache = new class (
            $lock->reveal(),
            $filesystem
        ) extends AnonymousConfigCache{
        };

        $this->assertTrue($configCache->isFresh());
    }

    /**
     * @test
     */
    public function itIsNotFreshIfTheLockIsNotAFile(): void
    {
        $lock = $this->prophesize(SplFileObject::class);
        $filesystem = new Filesystem();

        $lock->isFile()->willReturn(false);
        $lock->flock(LOCK_UN)->shouldBeCalled();

        $configCache = new class (
            $lock->reveal(),
            $filesystem,
            false
        ) extends AnonymousConfigCache{
        };

        $this->assertFalse($configCache->isFresh());
    }

    /**
     * @test
     */
    public function itIsNotFreshIfWeAreInDebugMode(): void
    {
        $lock = $this->prophesize(SplFileObject::class);
        $filesystem = new Filesystem();

        $lock->isFile()->willReturn(true);
        $lock->flock(LOCK_UN)->shouldBeCalled();

        $configCache = new class (
            $lock->reveal(),
            $filesystem,
            true
        ) extends AnonymousConfigCache{
        };

        $this->assertFalse($configCache->isFresh());
    }

    /**
     * @test
     */
    public function itWritesContentToTheLock(): void
    {
        $lock = $this->prophesize(SplFileObject::class);
        $filesystem = new Filesystem();

        $lock->isFile()->willReturn(true);

        $content = 'foo bar baz';

        $lock->flock(LOCK_UN)->shouldBeCalled();

        $configCache = new class (
            $lock->reveal(),
            $filesystem,
            true
        ) extends AnonymousConfigCache{
        };
    }
}
