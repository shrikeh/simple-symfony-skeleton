<?php

declare(strict_types=1);

namespace Tests\Unit\TestSymfonyApp\Kernel\Booter\ContainerLoader\ConfigCache;

use PHPUnit\Framework\TestCase;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ConfigCache\DebugConfigCache;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\CacheInvalidator\CacheInvalidatorInterface;
use SplFileObject;
use Symfony\Component\Filesystem\Filesystem;

final class DebugConfigCacheTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsFalseWhenItIsAskedIfItIsFresh(): void
    {
        $lock = $this->prophesize(SplFileObject::class);
        $invalidator = $this->prophesize(CacheInvalidatorInterface::class);

        $debugConfigCache = new DebugConfigCache(
            $lock->reveal(),
            new Filesystem(),
            $invalidator->reveal()
        );
        $this->assertFalse($debugConfigCache->isFresh());
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

        $lock->getPath()->willReturn($fakePath);
        $lock->rewind()->shouldBeCalled();
        $lock->ftruncate(0)->shouldBeCalled();
        $lock->fwrite($content)->shouldBeCalled();
        $lock->flock(LOCK_UN)->shouldBeCalled();

        $invalidator->invalidate($fakePath)->shouldBeCalled();

        $configCache = new DebugConfigCache(
            $lock->reveal(),
            new Filesystem(),
            $invalidator->reveal()
        );

        $configCache->write($content);
    }
}
