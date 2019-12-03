<?php

declare(strict_types=1);

namespace App\Kernel\Booter\ContainerLoader\ConfigCache;

use App\Kernel\Booter\ContainerLoader\ConfigCache\Traits\WriteContentTrait;
use App\Kernel\Booter\ContainerLoader\ConfigCache\Traits\WriteMetadataTrait;
use App\Kernel\Booter\ContainerLoader\ContainerCache\Invalidator\CacheInvalidatorInterface;
use SplFileObject;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class AnonymousConfigCache implements ConfigCacheInterface
{
    use WriteContentTrait;
    use WriteMetadataTrait;

    /**
     * @var CacheInvalidatorInterface
     */
    private CacheInvalidatorInterface $invalidator;

    /**
     * AnonymousConfigCache constructor.
     * @param SplFileObject $lock
     * @param Filesystem $filesystem
     * @param CacheInvalidatorInterface $invalidator
     */
    public function __construct(
        SplFileObject $lock,
        Filesystem $filesystem,
        CacheInvalidatorInterface $invalidator
    ) {
        $this->lock = $lock;
        $this->filesystem = $filesystem;
        $this->invalidator = $invalidator;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): string
    {
        return $this->lock->getPath();
    }

    /**
     * {@inheritDoc}
     */
    public function isFresh(): bool
    {
        return $this->lock->isFile();
    }

    /**
     * {@inheritDoc}
     */
    public function write($content, array $metadata = null): void
    {
        $this->writeAll($content, $metadata);
        $this->invalidator->invalidate($this->getPath());
    }
}
