<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Shrikeh\TestSymfonyApp\Console\Application;
use Shrikeh\TestSymfonyApp\Console\Kernel;
use Shrikeh\TestSymfonyApp\Deprecation\DeprecationsCollection;
use Shrikeh\TestSymfonyApp\Kernel\Booter\Booter;
use Shrikeh\TestSymfonyApp\Kernel\Booter\BooterInterface;
use Shrikeh\TestSymfonyApp\Kernel\Booter\BundleLoader\BundlerLoaderInterface;
use Shrikeh\TestSymfonyApp\Kernel\Booter\BundleLoader\FileBundleLoader;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\CacheInvalidator\CacheInvalidatorInterface;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\CacheInvalidator\OpcacheInvalidator;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\ContainerCacheInterface;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\CachePath;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper\ContainerDumperInterface;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper\Factory\ConfigCache;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper\Factory\ConfigCacheFactoryInterface;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper\SymfonyPhpDumper;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerLoader;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ContainerLoaderInterface;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler\BacktraceCleaner;
use Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler\BacktraceCleanerInterface;
use Shrikeh\TestSymfonyApp\Kernel\ConfigurationLoader\ConfigurationLoaderInterface;
use Shrikeh\TestSymfonyApp\Kernel\ConfigurationLoader\FileConfigurationLoader;
use Shrikeh\TestSymfonyApp\Kernel\Environment\Environment;
use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpKernel\KernelInterface;

final class KernelProvider implements ServiceProviderInterface
{
    /** @var string  */
    public const KEY_CACHE_FILE = 'shrikeh.cache.path.file';

    /**
     * @param Container|null $container
     * @return Container
     */
    public static function create(Container $container = null): Container
    {
        $container = $container ?? new Container();

        $container->register(new self());

        return $container;
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function register(Container $p): void
    {
        $p[InputInterface::class] = static function (): InputInterface {
            return new ArgvInput();
        };

        $p[Application::class] = static function (Container $c): Application {
            return new Application($c[KernelInterface::class]);
        };

        $p[KernelInterface::class] = static function (Container $c): KernelInterface {
            return new Kernel(
                $c[EnvironmentInterface::class],
                $c[BooterInterface::class],
                $c[ConfigurationLoaderInterface::class]
            );
        };

        $p[ConfigurationLoaderInterface::class] = static function (Container $c): ConfigurationLoaderInterface {
            return new FileConfigurationLoader(
                $c[EnvironmentInterface::class],
                dirname(__DIR__, 2) . '/config'
            );
        };

        $p[EnvironmentInterface::class] = static function (Container $c): EnvironmentInterface {
            return Environment::fromServerBag($c[ServerBag::class]);
        };

        $p[ServerBag::class] = static function (Container $c): ServerBag {
            return new ServerBag($c['server_variables']());
        };

        $p[BooterInterface::class] = static function (Container $c): BooterInterface {
            return new Booter(
                $c[BundlerLoaderInterface::class],
                $c[ContainerLoaderInterface::class],
                $c[EnvironmentInterface::class]
            );
        };

        $p[BundlerLoaderInterface::class] = static function (Container $c): BundlerLoaderInterface {
            return new FileBundleLoader(
                $c['bundles'],
                $c[EnvironmentInterface::class]
            );
        };

        $p[ContainerLoaderInterface::class] = static function (Container $c): ContainerLoaderInterface {
            return new ContainerLoader(
                $c[EnvironmentInterface::class],
                $c[DeprecationsHandler::class],
                $c[ContainerCacheInterface::class]
            );
        };

        $p[ContainerCacheInterface::class] = static function (Container $c): ContainerCacheInterface {
            return new FileContainerCache(
                $c[CachePath::class],
                $c[ContainerDumperInterface::class]
            );
        };

        $p[DeprecationsHandler::class] = static function (Container $c): DeprecationsHandler {
            return DeprecationsHandler::create(
                $c[DeprecationsCollection::class],
                $c[BacktraceCleanerInterface::class]
            );
        };

        $p[BacktraceCleanerInterface::class] = static function (): BacktraceCleanerInterface {
            return new BacktraceCleaner();
        };

        $p[DeprecationsCollection::class] = static function (): DeprecationsCollection {
            return new DeprecationsCollection();
        };

        $p[ContainerDumperInterface::class] = static function (Container $c): ContainerDumperInterface {
            return new SymfonyPhpDumper(
                $c[CachePath::class],
                $c[Filesystem::class],
                $c[ConfigCacheFactoryInterface::class]
            );
        };

        $p[CachePath::class] = static function (Container $c): CachePath {
            return CachePath::fromPath($c[static::KEY_CACHE_FILE]());
        };

        $p[Filesystem::class] = static function (): Filesystem {
            return new Filesystem();
        };

        $p[ConfigCacheFactoryInterface::class] = static function (
            Container $c
        ): ConfigCacheFactoryInterface {
            return new ConfigCache(
                $c[Filesystem::class],
                $c[CacheInvalidatorInterface::class]
            );
        };

        $p[CacheInvalidatorInterface::class] = static function (): CacheInvalidatorInterface {
            return new OpcacheInvalidator();
        };

        $p['server_variables'] = $p->protect(static function (): array {
            return $_SERVER;
        });


        $p['bundles'] = static function (): FileResource {
            return new FileResource(dirname(__DIR__, 2) . '/config/bundles.php');
        };

        $p[static::KEY_CACHE_FILE] = $p->protect(static function (): string {
            return '/cache/Container.php';
        });
    }
}
