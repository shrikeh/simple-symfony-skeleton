<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use App\Console\Application;
use Pimple\Container;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class ConsoleProvider implements ServiceProviderInterface
{
    /**
     * @return ContainerInterface
     */
    public static function serviceLocator(): ContainerInterface
    {
        return new ServiceLocator(static::create(), [
            Application::class,
            InputInterface::class
        ]);
    }

    /**
     * @param Container|null $container
     * @return Container
     */
    public static function create(Container $container = null): Container
    {
        if (!$container) {
            $container = new Container();
            $container->register(new KernelProvider());
        }

        $container->register(new self());

        return $container;
    }

    /**
     * ConsoleProvider constructor.
     * We make it private so there are only explicit ways to create it.
     */
    private function __construct()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function register(Container $p): void
    {
        $p[InputInterface::class] = static function (): InputInterface {
            return new ArgvInput();
        };

        $p[Application::class] = static function (Container $c): Application {
            return new Application($c[KernelInterface::class]);
        };
    }
}
