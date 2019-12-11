<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Kernel\Booter\ContainerLoader\ConfigCache\Traits;

use SplFileObject;

trait WriteContentTrait
{
    /** @var SplFileObject */
    private SplFileObject $lock;

    /**
     * Close the lock.
     */
    public function __destruct()
    {
        $this->lock->flock(LOCK_UN);
        unset($this->lock);
    }

    /**
     * @param $content
     * @param array|null $metadata
     */
    private function writeAll($content, array $metadata = null): void
    {
        $this->writeContent($content);
        if (null !== $metadata) {
            $this->writeMetadata($metadata);
        }
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
    abstract protected function writeMetadata(array $metaData): void;
}
