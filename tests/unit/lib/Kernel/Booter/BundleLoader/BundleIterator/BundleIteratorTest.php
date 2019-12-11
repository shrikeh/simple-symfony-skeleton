<?php

declare(strict_types=1);

namespace Tests\Unit\TestSymfonyApp\Kernel\Booter\BundleLoader\BundleIterator;

use Shrikeh\TestSymfonyApp\Kernel\Booter\BundleLoader\BundleIterator\BundleIterator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

final class BundleIteratorTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsTheBundles(): void
    {
        $firstName = 'first';
        $secondName = 'second';

        $firstBundle = $this->prophesize(BundleInterface::class);
        $secondBundle = $this->prophesize(BundleInterface::class);

        $firstBundle->getName()->willReturn($firstName);
        $secondBundle->getName()->willReturn($secondName);

        $firstBundle = $firstBundle->reveal();
        $secondBundle = $secondBundle->reveal();

        $bundleIterator = new BundleIterator([
            $firstBundle,
            $secondBundle
        ]);

        $bundles = $bundleIterator->getIterator();

        $this->assertSame($firstName, $bundles->key());
        $this->assertSame($firstBundle, $bundles->current());
        $bundles->next();
        $this->assertSame($secondName, $bundles->key());
        $this->assertSame($secondBundle, $bundles->current());
    }
}
