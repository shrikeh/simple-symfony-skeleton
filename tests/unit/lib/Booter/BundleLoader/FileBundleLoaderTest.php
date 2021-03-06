<?php

declare(strict_types=1);

namespace Tests\Unit\TestSymfonyApp\Booter\BundleLoader;

use Shrikeh\TestSymfonyApp\Booter\BundleLoader\FileBundleLoader;
use Shrikeh\TestSymfonyApp\Kernel\Environment\Environment;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

final class FileBundleLoaderTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsABundleIterator()
    {
        $bundlesFixturePath = FIXTURES_DIR . '/fakebundles.php';

        $fileResource = new FileResource($bundlesFixturePath);
        $environment = Environment::create(Environment::ENV_TEST);

        $bundleLoader = new FileBundleLoader(
            $fileResource,
            $environment
        );

        $bundles = iterator_to_array($bundleLoader->getBundles());

        $fixturedBundles = include $bundlesFixturePath;

        $this->assertCount(count($fixturedBundles), $bundles);

        foreach ($bundles as $bundle) {
            $this->assertInstanceOf(BundleInterface::class, $bundle);
        }
    }
}
