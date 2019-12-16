<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\ContainerLoader\ContainerCache\FileContainerCache\Dumper;

use Shrikeh\TestSymfonyApp\Booter\ContainerLoader\ContainerCache\FileContainerCache\CachePath;
use SplFileObject;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Filesystem\Filesystem;

final class SymfonyPhpDumper implements ContainerDumperInterface
{
    public const LOCK_MODE = LOCK_EX | LOCK_NB;

    /** @var CachePath */
    private CachePath $cachePath;

    /** @var Filesystem */
    private FileSystem $fileSystem;
    /**
     * @var Factory\ConfigCacheFactoryInterface
     */
    private Factory\ConfigCacheFactoryInterface $configCacheFactory;

    /**
     * SymfonyPhpDumper constructor.
     * @param CachePath $cachePath
     * @param Filesystem $fileSystem
     * @param Factory\ConfigCacheFactoryInterface $configCacheFactory
     */
    public function __construct(
        CachePath $cachePath,
        Filesystem $fileSystem,
        Factory\ConfigCacheFactoryInterface $configCacheFactory
    ) {
        $this->cachePath = $cachePath;
        $this->fileSystem = $fileSystem;
        $this->configCacheFactory = $configCacheFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function dumpContainer(ContainerBuilder $container, bool $debug = false): ContainerInterface
    {
        $errorLevel = error_reporting(\E_ALL ^ \E_WARNING);
        if (!$this->cachePath->exists()) {
            $this->fileSystem->mkdir($this->cachePath->getDir());
        }

        if ($lock = $this->getLock()) {
            $cache = $this->configCacheFactory->create($lock, $debug);

            // cache the container
            $dumper = new PhpDumper($container);

            $content = $dumper->dump([
                'file' => $this->cachePath->getFile(),
                'as_files' => true,
                'debug' => $debug,
                'build_time' => $this->getBuildTime($container),
            ]);

            $rootCode = array_pop($content);
            $dir = $this->cachePath->getDir();

            $mode = 0666 & ~umask();
            foreach ($content as $file => $code) {
                $this->dumpFile(sprintf('%s/%s', $dir, $file), $code, $mode);
            }

            $cache->write($rootCode, $container->getResources());
        }

        error_reporting($errorLevel);

        return $this->cachePath->read();
    }

    private function getBuildTime(ContainerBuilder $container): int
    {
        if ($container->hasParameter('kernel.container_build_time')) {
            return $container->getParameter('kernel.container_build_time');
        }

        return time();
    }

    /**
     * @param string $path
     * @param string $code
     * @param int $mode
     */
    private function dumpFile(string $path, string $code, int $mode): void
    {
        $this->fileSystem->dumpFile($path, $code);
        $this->fileSystem->chmod($path, $mode);
    }

    /**
     * @return SplFileObject|null
     */
    private function getLock(): ?SplFileObject
    {
        if ($lock = $this->cachePath->open()) {
            $lock->flock(static::LOCK_MODE, $wouldBlock);
        }

        return $lock;
    }
}
