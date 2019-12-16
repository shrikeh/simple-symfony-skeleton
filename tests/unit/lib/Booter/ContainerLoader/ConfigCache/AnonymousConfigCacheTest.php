<?php

declare(strict_types=1);

namespace Tests\Unit\TestSymfonyApp\Booter\ContainerLoader\ConfigCache;

use PHPUnit\Framework\TestCase;
use Shrikeh\TestSymfonyApp\Booter\ContainerLoader\CacheInvalidator\CacheInvalidatorInterface;
use Shrikeh\TestSymfonyApp\Booter\ContainerLoader\ConfigCache\AnonymousConfigCache;
use SplFileObject;
use Symfony\Component\Filesystem\Filesystem;

final class AnonymousConfigCacheTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsTrueIfItIsFresh(): void
    {
        $lock = $this->prophesize(SplFileObject::class);
        $invalidator = $this->prophesize(CacheInvalidatorInterface::class);
        $filesystem = new Filesystem();

        $lock->isFile()->willReturn(true);
        $lock->flock(LOCK_UN)->shouldBeCalled();

        $configCache = new class (
            $lock->reveal(),
            $filesystem,
            $invalidator->reveal()
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
        $invalidator = $this->prophesize(CacheInvalidatorInterface::class);
        $filesystem = new Filesystem();

        $lock->isFile()->willReturn(false);
        $lock->flock(LOCK_UN)->shouldBeCalled();

        $configCache = new class (
            $lock->reveal(),
            $filesystem,
            $invalidator->reveal()
        ) extends AnonymousConfigCache{
        };

        $this->assertFalse($configCache->isFresh());
    }

    /**
     * @test
     */
    public function itWritesContentToTheLock(): void
    {
        $fakePath = '/not/real';
        $content = 'foo bar baz';

        $lock = $this->prophesize(SplFileObject::class);
        $invalidator = $this->prophesize(CacheInvalidatorInterface::class);
        $filesystem = new Filesystem();

        $lock->getPathname()->willReturn($fakePath);
        $lock->rewind()->shouldBeCalled();
        $lock->ftruncate(0)->shouldBeCalled();
        $lock->fwrite($content)->shouldBeCalled();
        $lock->flock(LOCK_UN)->shouldBeCalled();

        $invalidator->invalidate($fakePath)->shouldBeCalled();

        $configCache = new class (
            $lock->reveal(),
            $filesystem,
            $invalidator->reveal()
        ) extends AnonymousConfigCache{
        };

        $configCache->write($content);
    }

    /**
     * @test
     */
    public function itWritesTheMetaData(): void
    {
        $fakePath = '/not/real';
        $content = 'foo bar baz';

        $lock = $this->prophesize(SplFileObject::class);
        $invalidator = $this->prophesize(CacheInvalidatorInterface::class);
        $filesystem = $this->prophesize(Filesystem::class);

        $lock->getPathname()->willReturn($fakePath);
        $lock->rewind()->shouldBeCalled();
        $lock->ftruncate(0)->shouldBeCalled();
        $lock->fwrite($content)->shouldBeCalled();
        $lock->flock(LOCK_UN)->shouldBeCalled();

        $invalidator->invalidate($fakePath)->shouldBeCalled();
        $metaPath = sprintf('%s.meta', $fakePath);

        $metaData = [
            'foo' => 'bar',
            'baz' => 'boo',
        ];

        $chmod = 0666 & ~umask();

        $filesystem->dumpFile($metaPath, serialize($metaData))->shouldBeCalled();
        $filesystem->chmod($metaPath, $chmod)->shouldBeCalled();

        $configCache = new class (
            $lock->reveal(),
            $filesystem->reveal(),
            $invalidator->reveal()
        ) extends AnonymousConfigCache{
        };

        $configCache->write($content, $metaData);
    }
}
