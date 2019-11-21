<?php

declare(strict_types=1);

namespace App;

use App\Kernel\Exception\UnrecognisedEnvironment;
use App\Kernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

use function dirname;

class Kernel extends BaseKernel implements KernelInterface
{
    use MicroKernelTrait;

    /** @var string  */
    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * @param bool|null $debug
     * @return Kernel
     */
    public static function fromServer(bool $debug = null): self
    {
        $env = $_SERVER['APP_ENV'];
        $debug = $debug ?? (bool) $_SERVER['APP_DEBUG'];

        return new self($env, $debug);
    }

    /**
     * Kernel constructor.
     * @param string $environment
     * @param bool $debug
     * @throws UnrecognisedEnvironment
     */
    public function __construct(string $environment, bool $debug)
    {
        if (!in_array($environment, static::ALLOWED_ENVS, true)) {
            throw UnrecognisedEnvironment::create($environment);
        }
        parent::__construct($environment, $debug);
    }

    /**
     * @return iterable
     */
    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir() . '/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir(): string
    {
        return $_SERVER['SYMFONY_CACHE_DIR'] ?? parent::getCacheDir();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir(): string
    {
        return $_SERVER['SYMFONY_LOG_DIR'] ?? parent::getLogDir();
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface $loader
     * @throws \Exception
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir() . '/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir() . '/config';

        $loader->load($confDir . '/app.yml');

        $loader->load($confDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{packages}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');
    }

    /**
     * {@inheritDoc}
     */
    private function configureRoutes(RouteCollectionBuilder $routes): void
    {
    }
}
