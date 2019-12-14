<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use Generator;
use Pimple\Container;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;
use Shrikeh\TestSymfonyApp\Kernel\Booter\BundleLoader\BundleIterator\BundleIterator;
use Shrikeh\TestSymfonyApp\Kernel\Booter\BundleLoader\BundlerLoaderInterface;
use Shrikeh\TestSymfonyApp\Kernel\Booter\BundleLoader\PsrContainerBundleLoader;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;

final class BundleProvider implements ServiceProviderInterface
{
    public const BUNDLES = __CLASS__ . '.bundles';

    public const BUNDLE_SERVICE_LOCATOR = self::BUNDLES . '.service_locator';

    public const BUNDLES_KEY = self::BUNDLES . 'key';

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
            yield $c[FrameworkBundle::class];
        };

        $pimple[FrameworkBundle::class] = static function (): FrameworkBundle {
            return new FrameworkBundle();
        };

        $pimple[self::BUNDLES_KEY] = PsrContainerBundleLoader::DEFAULT_BUNDLE_KEY;
    }
}
