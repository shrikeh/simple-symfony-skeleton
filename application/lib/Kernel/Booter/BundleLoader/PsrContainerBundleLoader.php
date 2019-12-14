<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Booter\BundleLoader;

use Generator;
use Psr\Container\ContainerInterface;
use Shrikeh\TestSymfonyApp\Kernel\Booter\BundleLoader\Exception\BundleContainerKeyNotFound;
use Shrikeh\TestSymfonyApp\Kernel\Booter\BundleLoader\Exception\BundlesNotIterable;

final class PsrContainerBundleLoader implements BundlerLoaderInterface
{
    public const DEFAULT_BUNDLE_KEY = 'app.bundles';

    /** @var ContainerInterface  */
    private ContainerInterface $bundleContainer;
    /**
     * @var string
     */
    private $key;

    /**
     * PsrContainerBundleLoader constructor.
     * @param ContainerInterface $container
     * @param string $key
     */
    public function __construct(ContainerInterface $container, string $key = self::DEFAULT_BUNDLE_KEY)
    {
        $this->bundleContainer = $container;
        $this->key = $key;
    }

    /**
     * @return iterable
     */
    public function getBundles(): iterable
    {
        yield from $this->getContainerBundles();
    }

    /**
     * @return Generator
     */
    private function getContainerBundles(): Generator
    {
        if (!$this->bundleContainer->has($this->key)) {
            throw BundleContainerKeyNotFound::fromKey($this->key);
        }

        $iterableBundles = $this->bundleContainer->get($this->key);

        if (!is_iterable($iterableBundles)) {
            throw BundlesNotIterable::create();
        }

        foreach ($iterableBundles as $key => $bundle) {
            yield $key => $bundle;
        }
    }
}
