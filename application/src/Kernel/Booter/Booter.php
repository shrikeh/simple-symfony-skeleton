<?php

declare(strict_types=1);

namespace App\Kernel\Booter;

use App\Kernel\Booter\BundleLoader\BundlerLoaderInterface;
use App\Kernel\Booter\ContainerLoader\ContainerLoaderInterface;
use App\Kernel\Environment\EnvironmentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class Booter implements BooterInterface
{
    private bool $booted = false;
    /**
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $container;
    /**
     * @var ContainerLoaderInterface
     */
    private ContainerLoaderInterface $containerLoader;
    /**
     * @var BundlerLoaderInterface
     */
    private BundlerLoaderInterface $bundlerLoader;
    /**
     * @var EnvironmentInterface
     */
    private EnvironmentInterface $environment;

    /**
     * Booter constructor.
     * @param BundlerLoaderInterface $bundlerLoader
     * @param ContainerLoaderInterface $containerLoader
     * @param EnvironmentInterface $environment
     */
    public function __construct(
        BundlerLoaderInterface $bundlerLoader,
        ContainerLoaderInterface $containerLoader,
        EnvironmentInterface $environment
    ) {
        $this->containerLoader = $containerLoader;
        $this->bundlerLoader = $bundlerLoader;
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getBundles(): iterable
    {
        yield from $this->bundlerLoader->getBundles();
    }

    /**
     * {@inheritdoc}
     */
    public function shutdown(): void
    {
        if (!$this->isBooted()) {
            return;
        }

        $this->booted = false;

        foreach ($this->getBundles() as $bundle) {
            $bundle->shutdown();
            $bundle->setContainer(null);
        }

        $this->container = null;
    }


    /**
     * @param KernelInterface $kernel
     */
    public function boot(KernelInterface $kernel): void
    {
        $this->booted = true;
        $this->environment->setDebugShellVerbosity();
        $this->initialiseBundles();

        $this->container = $this->initialiseContainer($kernel);

        foreach ($this->getBundles() as $bundle) {
            $bundle->setContainer($this->container);
            $bundle->boot();
        }
    }

    /**
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        if (!$this->isBooted()) {
            throw new \RuntimeException('Why u no boot?');
        }

        return $this->container;
    }

    /**
     * @return iterable
     */
    private function initialiseBundles(): iterable
    {
        yield from $this->bundlerLoader->getBundles();
    }

    /**
     * @param KernelInterface $kernel
     * @return ContainerInterface
     */
    private function initialiseContainer(KernelInterface $kernel): ContainerInterface
    {
        return $this->containerLoader->loadContainer($kernel);
    }
}
