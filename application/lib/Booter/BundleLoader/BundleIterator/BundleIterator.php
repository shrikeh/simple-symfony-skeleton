<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\BundleLoader\BundleIterator;

use ArrayAccess;
use Ds\Map;
use Generator;
use IteratorAggregate;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

final class BundleIterator implements IteratorAggregate, ArrayAccess
{
    /**
     * @var Map
     */
    private Map $bundles;

    /**
     * BundleIterator constructor.
     * @param iterable $bundles
     */
    public function __construct(iterable $bundles)
    {
        $this->bundles = new Map();
        foreach ($bundles as $bundle) {
            $this->addBundle($bundle, $bundle->getName());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): Generator
    {
        foreach ($this->bundles as $name => $bundle) {
            yield $name => $bundle;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($bundleName): bool
    {
        return $this->bundles->hasKey($bundleName);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($bundleName): BundleInterface
    {
        return $this->bundles->get($bundleName);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($bundleName, $bundle)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * @param BundleInterface $bundle
     * @param string $bundleName
     */
    private function addBundle(BundleInterface $bundle, string $bundleName): void
    {
        $this->bundles->put($bundleName, $bundle);
    }
}
