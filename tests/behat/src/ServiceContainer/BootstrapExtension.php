<?php

declare(strict_types=1);

namespace Tests\Behat\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Closure;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class BootstrapExtension implements Extension
{
    public const CONFIG_KEY = 'bootstrap';

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container): void
    {

    }

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey(): string
    {
        return self::CONFIG_KEY;
    }

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        // TODO: Implement initialize() method.
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('path')
            ->defaultValue('bootstrap.php')
            ->end();
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $basePath = $container->getParameter('paths.base');
        $bootstrapPath = $config['path'];

        if ($bootstrapPath) {
            if (file_exists($bootstrap = $basePath . '/' . $bootstrapPath)) {
                $this->loadBootstrap($bootstrap);
            } elseif (file_exists($bootstrapPath)) {
                $this->loadBootstrap($bootstrapPath);
            }
        }
    }



    /**
     * @param string $path
     */
    private function loadBootstrap(string $path): void
    {
        $bootstrapLoader = Closure::fromCallable(static function ($path): void {
            require_once $path;
        });

        $bootstrapLoader($path);
    }
}