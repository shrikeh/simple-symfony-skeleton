<?php

declare(strict_types=1);

namespace App\Kernel\Booter\ContainerLoader\ConfigCache;

use SplFileObject;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\Filesystem\Filesystem;

use function filter_var;
use function function_exists;
use function ini_get;
use function opcache_invalidate;

abstract class AnonymousConfigCache implements ConfigCacheInterface
{
    /** @var SplFileObject */
    private SplFileObject $lock;

    /** @var bool  */
    private bool $debug;
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * AnonymousConfigCache constructor.
     * @param SplFileObject $lock
     * @param Filesystem $filesystem
     * @param bool $debug
     */
    public function __construct(SplFileObject $lock, Filesystem $filesystem, bool $debug = false)
    {
        $this->lock = $lock;
        $this->filesystem = $filesystem;
        $this->debug = $debug;
    }

    /**
     * Close the lock.
     */
    public function __destruct()
    {
        $this->lock->flock(LOCK_UN);
        unset($this->lock);
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
        if (!$this->debug && $this->lock->isFile()) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function write($content, array $metadata = null): void
    {
        $this->writeContent($content);
        if (null !== $metadata) {
            $this->writeMetadata($metadata);
        }

        $this->invalidateOpcache();
    }

    /**
     * @param string $content
     */
    private function writeContent(string $content): void
    {
        $this->lock->rewind();
        $this->lock->ftruncate(0);
        $this->lock->fwrite($content);
    }

    /**
     * @param array $metaData
     */
    private function writeMetadata(array $metaData): void
    {
        $metaFilePath = sprintf('%s.meta', $this->getPath());
        $this->filesystem->dumpFile($metaFilePath, serialize($metaData));
        $this->filesystem->chmod($metaFilePath, 0666 & ~umask());
    }

    /**
     * Invalidate the opcache if it exists
     */
    private function invalidateOpcache(): void
    {
        if (!function_exists('opcache_invalidate')) {
            return;
        }
        if (filter_var(ini_get('opcache.enable'), FILTER_VALIDATE_BOOLEAN)) {
            opcache_invalidate($this->getPath(), true);
        }
    }
}
