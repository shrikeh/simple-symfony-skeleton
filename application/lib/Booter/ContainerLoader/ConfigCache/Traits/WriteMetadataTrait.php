<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\ContainerLoader\ConfigCache\Traits;

use Symfony\Component\Filesystem\Filesystem;

trait WriteMetadataTrait
{
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * @param array $metaData
     */
    private function writeMetadata(array $metaData): void
    {
        $metaFilePath = sprintf('%s.meta', $this->getPath());
        $this->filesystem->dumpFile($metaFilePath, serialize($metaData));
        $this->filesystem->chmod($metaFilePath, 0666 & ~umask());
    }
}
