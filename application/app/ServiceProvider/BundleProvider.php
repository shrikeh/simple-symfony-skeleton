<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use Generator;
use Pimple\Container;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;
use Shrikeh\TestSymfonyApp\Booter\BundleLoader\BundleIterator\BundleIterator;
use Shrikeh\TestSymfonyApp\Booter\BundleLoader\BundlerLoaderInterface;
use Shrikeh\TestSymfonyApp\Booter\BundleLoader\PsrContainerBundleLoader;
use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Tests\Utils\TestCaseBundle\TestCaseBundle;

final class BundleProvider implements ServiceProviderInterface
{
    /** @var string  */
    public const BUNDLES = __CLASS__ . '.bundles';

    /** @var string  */
    public const BUNDLE_SERVICE_LOCATOR = self::BUNDLES . '.service_locator';

    /** @var string  */
    public const BUNDLES_KEY = self::BUNDLES . 'key';

    /** @var string  */
    public const BUNDLES_ENVS = self::BUNDLES . '.envs';

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple[BundlerLoaderInterface::class] = static function (Container $c): BundlerLoaderInterface {
            return $c[PsrContainerBundleLoader::class];
        };

        $pimple[PsrContainerBundleLoader::class] = static function (Container $c) {
            return new PsrContainerBundleLoader(
                $c[self::BUNDLE_SERVICE_LOCATOR],
                $c[self::BUNDLES_KEY]
            );
        };

        $pimple[self::BUNDLE_SERVICE_LOCATOR] = static function (Container $c) {
            return new ServiceLocator($c, [$c[self::BUNDLES_KEY]]);
        };

        $pimple[PsrContainerBundleLoader::DEFAULT_BUNDLE_KEY] = $pimple->factory(
            static function (Container $c): iterable {
                /** @var BundleIterator $bundleIterator */
                $bundleIterator = $c[BundleIterator::class];

                yield from $bundleIterator->getIterator();
            }
        );

        $pimple[BundleIterator::class] = static function (Container $c): BundleIterator {
            return new BundleIterator($c[self::BUNDLES]);
        };

        $pimple[self::BUNDLES] = static function (Container $c): Generator {
            $environment = $c[EnvironmentInterface::class];

            foreach ($c[self::BUNDLES_ENVS] as $class => $envs) {
                if ($envs[$environment->getName()] ?? $envs[BundlerLoaderInterface::ENVIRONMENTS_ALL] ?? false) {
                    if (class_exists($class)) {
                        yield $c[$class];
                    }
                }
            }
        };

        $pimple[self::BUNDLES_ENVS] = [
            FrameworkBundle::class => ['all' => true],
            TestCaseBundle::class => [EnvironmentInterface::ENV_DEV => true],
        ];

        $pimple[FrameworkBundle::class] = static function (): FrameworkBundle {
            return new FrameworkBundle();
        };

        $pimple[TestCaseBundle::class] = static function (): TestCaseBundle {
            return new TestCaseBundle();
        };

        $pimple[self::BUNDLES_KEY] = PsrContainerBundleLoader::DEFAULT_BUNDLE_KEY;
    }
}
