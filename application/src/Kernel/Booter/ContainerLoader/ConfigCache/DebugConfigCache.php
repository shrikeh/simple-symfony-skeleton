<?php

declare(strict_types=1);

namespace App\Kernel\Booter\ContainerLoader\ConfigCache;

use App\Kernel\Booter\ContainerLoader\ConfigCache\Traits\WriteContentTrait;
use App\Kernel\Booter\ContainerLoader\ConfigCache\Traits\WriteMetadataTrait;
use SplFileObject;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\Config\Resource\ResourceInterface;
use Symfony\Component\Filesystem\Filesystem;

final class DebugConfigCache implements ConfigCacheInterface
{
    use WriteContentTrait;
    use WriteMetadataTrait;

    /**
     * DebugConfigCache constructor.
     * @param SplFileObject $lock
     * @param Filesystem $filesystem
     */
    public function __construct(
        SplFileObject $lock,
        Filesystem $filesystem
    ) {
        $this->lock = $lock;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): string
    {
        return $this->lock->getPath();
    }

    /**
     * Checks if the cache is still fresh.
     *
     * This check should take the metadata passed to the write() method into consideration.
     *
     * @return bool Whether the cache is still fresh
     */
    public function isFresh()
    {
        return false;
    }

    /**
     * Writes the given content into the cache file. Metadata will be stored
     * independently and can be used to check cache freshness at a later time.
     *
     * @param string $content The content to write into the cache
     * @param ResourceInterface[]|null $metadata An array of ResourceInterface instances
     *
     * @throws \RuntimeException When the cache file cannot be written
     */
    public function write($content, array $metadata = null)
    {
        $this->writeAll($content, $metadata);
    }
}