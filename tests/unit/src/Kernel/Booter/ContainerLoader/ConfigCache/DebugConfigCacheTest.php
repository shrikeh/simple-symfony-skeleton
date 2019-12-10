<?php

declare(strict_types=1);

namespace Tests\Unit\App\Kernel\Booter\ContainerLoader\ConfigCache;

use App\Kernel\Booter\ContainerLoader\ConfigCache\AnonymousConfigCache;
use App\Kernel\Booter\ContainerLoader\ConfigCache\DebugConfigCache;
use App\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Invalidator\CacheInvalidatorInterface;
use PHPUnit\Framework\TestCase;
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
