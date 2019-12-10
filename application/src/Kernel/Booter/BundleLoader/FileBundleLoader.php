<?php

declare(strict_types=1);

namespace App\Kernel\Booter\BundleLoader;

use App\Kernel\Booter\BundleLoader\BundleIterator\BundleIterator;
use App\Kernel\Environment\EnvironmentInterface;
use Closure;
use Generator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

final class FileBundleLoader implements BundlerLoaderInterface
{
    /** @var string  */
    public const ENVIRONMENTS_ALL = 'all';
    /**
     * @var FileResource
     */
    private FileResource $bundleResource;

    /**
     * @var iterable
     */
    private ?BundleIterator $bundles;
    /**
     * @var EnvironmentInterface
     */
    private EnvironmentInterface $environment;

    /**
     * FileBundleLoader constructor.
     * @param FileResource $bundleResource
     * @param EnvironmentInterface $environment
     */
    public function __construct(FileResource $bundleResource, EnvironmentInterface $environment)
    {
        $this->bundleResource = $bundleResource;
        $this->environment = $environment;
    }

    /**
     * @return iterable
     */
    public function getBundles(): iterable
    {
        yield from $this->getBundleIterator();
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function addContainerResource(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addResource($this->bundleResource);
    }

    /**
     * @return BundleIterator
     */
    private function getBundleIterator(): BundleIterator
    {
        if (!isset($this->bundles)) {
            $this->bundles = new BundleIterator($this->loadBundles());
        }

        return $this->bundles;
    }

    /**
     * @return Generator
     */
    private function loadBundles(): Generator
    {
        foreach ($this->getBundlesFromFile() as $class => $envs) {
            if ($bundle = $this->initEnvBundle($class, $envs)) {
                yield $bundle->getName() => $bundle;
            }
        }
    }

    /**
     * @param string $class
     * @param array $envs
     * @return BundleInterface|null
     */
    private function initEnvBundle(string $class, array $envs): ?BundleInterface
    {
        if ($envs[$this->environment->getName()] ?? $envs[static::ENVIRONMENTS_ALL] ?? false) {
            if (class_exists($class)) {
                return new $class();
            }
        }

        return null;
    }

    /**
     * @return iterable
     */
    private function getBundlesFromFile(): iterable
    {
        $bundles = Closure::fromCallable(static function ($path): iterable {
            return require $path;
        });

        return $bundles($this->bundleResource->getResource());
    }
}
