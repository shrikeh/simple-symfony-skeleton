<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use App\Console\Application;
use App\Console\Kernel;
use App\Kernel\Booter\Booter;
use App\Kernel\Booter\BooterInterface;
use App\Kernel\Booter\BundleLoader\BundlerLoaderInterface;
use App\Kernel\Booter\BundleLoader\FileBundleLoader;
use App\Kernel\ConfigurationLoader\ConfigurationLoaderInterface;
use App\Kernel\ConfigurationLoader\FileConfigurationLoader;
use App\Kernel\Booter\ContainerLoader\ContainerCache\ContainerCacheInterface;
use App\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache;
use App\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\CachePath;
use App\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper\ContainerDumperInterface;
use App\Kernel\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper\SymfonyPhpDumper;
use App\Kernel\Booter\ContainerLoader\ContainerLoader;
use App\Kernel\Booter\ContainerLoader\ContainerLoaderInterface;
use App\Kernel\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler;
use App\Kernel\Environment\Environment;
use App\Kernel\Environment\EnvironmentInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
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

        $p[DeprecationsHandler::class] = static function (): DeprecationsHandler {
            return new DeprecationsHandler();
        };

        $p[ContainerDumperInterface::class] = static function (Container $c): ContainerDumperInterface {
            return new SymfonyPhpDumper(
                $c[CachePath::class],
                $c[Filesystem::class]
            );
        };

        $p[CachePath::class] = static function (Container $c): CachePath {
            return CachePath::fromPath($c[static::KEY_CACHE_FILE]());
        };

        $p[Filesystem::class] = static function (): Filesystem {
            return new Filesystem();
        };

        $p['server_variables'] = $p->protect(static function (): array {
            return $_SERVER;
        });

        $p['environment'] = $p->protect(static function (): string {
            return EnvironmentInterface::ENV_PROD;
        });

        $p['bundles'] = static function (): FileResource {
            return new FileResource(dirname(__DIR__, 2) . '/config/bundles.php');
        };

        $p[static::KEY_CACHE_FILE] = $p->protect(static function (): string {
            return '/var/cache/symfony/Container.php';
        });
    }
}
