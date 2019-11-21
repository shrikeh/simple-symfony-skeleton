<?php

declare(strict_types=1);

namespace App;

use App\Kernel\Exception\UnrecognisedEnvironment;
use App\Kernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

use function dirname;

class Kernel extends BaseKernel implements KernelInterface
{
    use MicroKernelTrait;

    public const SERVER_APP_ENV = 'APP_ENV';
    public const SERVER_APP_DEBUG = 'APP_DEBUG';
    public const SERVER_CACHE_DIR = 'SYMFONY_CACHE_DIR';
    public const SERVER_LOG_DIR = 'SYMFONY_LOG_DIR';

    /** @var string  */
    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';
    /**
     * @var ServerBag
     */
    private $serverBag;

    /**
     * @param ServerBag $serverBag
     * @param bool|null $debug
     * @return Kernel
     */
    public static function fromServerBag(ServerBag $serverBag, bool $debug = null): self
    {
        $env = $serverBag->get(static::SERVER_APP_ENV);
        $debug = $debug ?? $serverBag->getBoolean(static::SERVER_APP_DEBUG);

        return new static($serverBag, $env, $debug);

    }

    /**
     * Kernel constructor.
     * @param ServerBag $serverBag
     * @param string $environment
     * @param bool $debug
     */
    public function __construct(ServerBag $serverBag, string $environment, bool $debug)
    {
        if (!in_array($environment, static::ALLOWED_ENVS, true)) {
            throw UnrecognisedEnvironment::create($environment);
        }
        parent::__construct($environment, $debug);

        $this->serverBag = $serverBag;
    }

    /**
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booted;
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
        return $this->serverBag->get(static::SERVER_CACHE_DIR, parent::getCacheDir());
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir(): string
    {
        return $this->serverBag->get(static::SERVER_LOG_DIR, parent::getLogDir());
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface $loader
     * @throws \Exception
     */
    private function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
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
