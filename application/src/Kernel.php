<?php

declare(strict_types=1);

namespace App;

use App\Kernel\Booter\BooterInterface;
use App\Kernel\ConfigurationLoader\ConfigurationLoaderInterface;
use App\Kernel\Environment\EnvironmentInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

final class Kernel implements KernelInterface
{
    public const SERVER_CACHE_DIR = 'SYMFONY_CACHE_DIR';
    public const SERVER_LOG_DIR = 'SYMFONY_LOG_DIR';

    /**
     * @var EnvironmentInterface
     */
    private EnvironmentInterface $environment;
    /**
     * @var BooterInterface
     */
    private BooterInterface $booter;
    /**
     * @var ConfigurationLoaderInterface
     */
    private ConfigurationLoaderInterface $configurationLoader;

    /**
     * Kernel constructor.
     * @param EnvironmentInterface $environment
     * @param BooterInterface $booter
     * @param ConfigurationLoaderInterface $configurationLoader
     */
    public function __construct(
        EnvironmentInterface $environment,
        BooterInterface $booter,
        ConfigurationLoaderInterface $configurationLoader
    ) {
        $this->environment = $environment;
        $this->booter = $booter;
        $this->configurationLoader = $configurationLoader;
    }

    /**
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booter->isBooted();
    }

    /**
     * {@inheritDoc}
     */
    public function getCharset(): string
    {
        return $this->environment->getCharset();
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        // TODO: Implement handle() method.
    }

    /**
     * {@inheritDoc}
     */
    public function registerBundles(): iterable
    {
        return $this->booter->getBundles();
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        $this->booter->boot($this);
    }

    /**
     * {@inheritDoc}
     */
    public function shutdown(): void
    {
        $this->booter->shutdown();
    }

    /**
     * {@inheritDoc}
     */
    public function getBundles(): iterable
    {
        return $this->booter->getBundles();
    }

    /**
     * {@inheritDoc}
     */
    public function getBundle($name): Bundle
    {
        // TODO: Implement getBundle() method.
    }

    /**
     * {@inheritDoc}
     */
    public function locateResource($name)
    {
        // TODO: Implement locateResource() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getEnvironment(): string
    {
        return $this->environment->getEnvironmentName();
    }

    /**
     * {@inheritDoc}
     */
    public function isDebug(): bool
    {
        return $this->environment->isDebug();
    }

    /**
     * @return string
     */
    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    /**
     * {@inheritDoc}
     */
    public function getRootDir(): string
    {
        return $this->getProjectDir();
    }

    /**
     * {@inheritDoc}
     */
    public function getContainer(): ContainerInterface
    {
        return $this->booter->getContainer();
    }

    /**
     * {@inheritDoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $this->configurationLoader->loadConfig($loader);
    }

    /**
     * {@inheritDoc}
     */
    public function getStartTime()
    {
        // TODO: Implement getStartTime() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheDir(): string
    {
        return $this->getFromServerBag(
            self::SERVER_CACHE_DIR,
            $this->getProjectDir() . '/var/cache'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getLogDir()
    {
        return $this->getFromServerBag(
            self::SERVER_LOG_DIR,
            $this->getProjectDir() . '/var/logs'
        );
    }

    /**
     * @param string $key
     * @param null $defaultValue
     * @return string
     */
    private function getFromServerBag(string $key, $defaultValue = null): ?string
    {
        return $this->environment->getServerBag()->get($key, $defaultValue);
    }
}
