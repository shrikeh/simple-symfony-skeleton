<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\ContainerLoader;

use Shrikeh\TestSymfonyApp\Booter\ContainerLoader\ContainerCache\ContainerCacheInterface;
use Shrikeh\TestSymfonyApp\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler;
use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use InvalidArgumentException;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\MergeExtensionConfigurationPass;
use Symfony\Component\HttpKernel\KernelInterface;

use function defined;
use function get_class;

final class ContainerLoader implements ContainerLoaderInterface
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;
    /**
     * @var EnvironmentInterface
     */
    private EnvironmentInterface $environment;
    /**
     * @var DeprecationsHandler
     */
    private DeprecationsHandler $deprecationsHandler;
    /**
     * @var ContainerCacheInterface
     */
    private $containerCache;

    /**
     * ContainerLoader constructor.
     * @param EnvironmentInterface $environment
     * @param DeprecationsHandler $deprecationsHandler
     * @param ContainerCacheInterface $containerCache
     */
    public function __construct(
        EnvironmentInterface $environment,
        DeprecationsHandler $deprecationsHandler,
        ContainerCacheInterface $containerCache
    ) {
        $this->environment = $environment;
        $this->deprecationsHandler = $deprecationsHandler;
        $this->containerCache = $containerCache;
    }

    /**
     * @param KernelInterface $kernel
     * @return ContainerInterface
     */
    public function loadContainer(KernelInterface $kernel): ContainerInterface
    {
        if (!$container = $this->containerCache->loadCachedContainer($this->environment)) {
            $container = $this->buildContainer($kernel);
            $container = $this->containerCache->saveContainerBuilder($container);
        }
        $this->container = $container;

        return $this->setContainerKernel($kernel);
    }

    /**
     * @param KernelInterface $kernel
     * @return ContainerBuilder
     */
    private function buildContainer(KernelInterface $kernel): ContainerBuilder
    {
        $collectDeprecations = $kernel->isDebug() && !defined('PHPUNIT_COMPOSER_INSTALL');

        if ($collectDeprecations) {
            $this->deprecationsHandler->register();
        }

        $container = $this->getContainerBuilder($kernel);

        $this->prepareContainer($container, $kernel);

        $container->compile();

        if ($collectDeprecations) {
            $this->deprecationsHandler->restore();

//            file_put_contents($cacheDir.'/'.$class.'Deprecations.log', serialize(array_values($collectedLogs)));
//            file_put_contents($cacheDir.'/'.$class.'Compiler.log', null !== $container ? implode("\n", $container->getCompiler()->getLog()) : '');
        }

        return $container;
    }

    /**
     * Prepares the ContainerBuilder before it is compiled.
     * @param ContainerBuilder $container
     * @param KernelInterface $kernel
     */
    private function prepareContainer(ContainerBuilder $container, KernelInterface $kernel): void
    {
        $extensions = [];
        foreach ($kernel->getBundles() as $bundle) {
            if ($extension = $bundle->getContainerExtension()) {
                $container->registerExtension($extension);
            }

            if ($this->environment->isDebug()) {
                $container->addObjectResource($bundle);
            }
        }

        foreach ($kernel->getBundles() as $bundle) {
            $bundle->build($container);
        }

        foreach ($container->getExtensions() as $extension) {
            $extensions[] = $extension->getAlias();
        }

        // ensure these extensions are implicitly loaded
        $container->getCompilerPassConfig()->setMergePass(new MergeExtensionConfigurationPass($extensions));
        $container->setParameter('container.dumper.inline_class_loader', true);
        $cont = $kernel->registerContainerConfiguration($this->getContainerLoader(
            $kernel,
            $container
        ));
        if (null !== $cont) {
            $container->merge($cont);
        }
    }

    /**
     * Returns a loader for the container.
     *
     * @param KernelInterface $kernel
     * @param ContainerBuilder $container
     * @return DelegatingLoader The loader
     */
    private function getContainerLoader(KernelInterface $kernel, ContainerBuilder $container): DelegatingLoader
    {
        $locator = new FileLocator($kernel);
        $resolver = new LoaderResolver([
            new XmlFileLoader($container, $locator),
            new YamlFileLoader($container, $locator),
            new IniFileLoader($container, $locator),
            new PhpFileLoader($container, $locator),
            new GlobFileLoader($container, $locator),
            new DirectoryLoader($container, $locator),
            new ClosureLoader($container),
        ]);

        return new DelegatingLoader($resolver);
    }

    /**
     * @param KernelInterface $kernel
     * @return ContainerBuilder
     */
    private function getContainerBuilder(KernelInterface $kernel): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->getParameterBag()->add($this->getKernelParameters($kernel));
        $container->addObjectResource($kernel);

        return $container;
    }

    /**
     * @param KernelInterface $kernel
     * @return array
     */
    private function getKernelParameters(KernelInterface $kernel): array
    {
        $bundles = [];
        $bundlesMetadata = [];

        foreach ($kernel->getBundles() as $name => $bundle) {
            $bundles[$name] = get_class($bundle);
            $bundlesMetadata[$name] = [
                'path' => $bundle->getPath(),
                'namespace' => $bundle->getNamespace(),
            ];
        }

        return [
            /*
             * @deprecated since Symfony 4.2, use kernel.project_dir instead
             */
            'kernel.root_dir' => $kernel->getProjectDir(),
            'kernel.project_dir' => $kernel->getProjectDir(),
            'kernel.environment' => $this->environment->getName(),
            'kernel.debug' => $this->environment->isDebug(),
            /*
             * @deprecated since Symfony 4.2
             */
            'kernel.name' => $kernel->getName(),
            'kernel.cache_dir' => $kernel->getCacheDir(),
            'kernel.logs_dir' => $kernel->getLogDir(),
            'kernel.bundles' => $bundles,
            'kernel.bundles_metadata' => $bundlesMetadata,
            'kernel.charset' => $this->environment->getCharset(),
            'kernel.container_class' => $this->getKernelContainerClass($kernel),
        ];
    }

    /**
     * @param KernelInterface $kernel
     * @return ContainerInterface
     */
    private function setContainerKernel(KernelInterface $kernel): ContainerInterface
    {
        $this->container->set('kernel', $kernel);

        return $this->container;
    }

    /**
     * Gets the container class.
     *
     * @param KernelInterface $kernel
     * @return string The container class
     */
    private function getKernelContainerClass(KernelInterface $kernel): string
    {
        $class = \get_class($kernel);
        $class = 0 === strpos($class, 'c') && 0 === strpos($class, "class@anonymous\0") ? get_parent_class($class) . str_replace('.', '_', ContainerBuilder::hash($class)) : $class;
        $envName = $this->environment->getName();
        $debug = $this->environment->isDebug();

        $class = $kernel->getName() . str_replace('\\', '_', $class) . ucfirst($envName) . ($debug ? 'Debug' : '') . 'Container';

        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $class)) {
            throw new InvalidArgumentException(sprintf('The environment "%s" contains invalid characters, it can only contain characters allowed in PHP class names.', $this->environment));
        }

        return $class;
    }
}
