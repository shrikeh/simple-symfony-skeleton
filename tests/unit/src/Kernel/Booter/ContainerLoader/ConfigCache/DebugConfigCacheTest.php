<?php

declare(strict_types=1);

namespace Tests\Unit\App\Kernel\Booter\ContainerLoader\ConfigCache;

use App\Kernel\Booter\ContainerLoader\ConfigCache\DebugConfigCache;
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

        $debugConfigCache = new DebugConfigCache($lock->reveal(), new Filesystem());
        $this->assertFalse($debugConfigCache->isFresh());
    }
}