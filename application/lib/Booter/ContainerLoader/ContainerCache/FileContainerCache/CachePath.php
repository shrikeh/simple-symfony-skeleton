<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\ContainerLoader\ContainerCache\FileContainerCache;

use SplFileInfo;
use SplFileObject;

final class CachePath
{
    public const OPEN_MODE = 'w';

    /** @var SplFileInfo  */
    private SplFileInfo $cacheFile;

    /**
     * @param string $path
     * @return CachePath
     */
    public static function fromPath(string $path): self
    {
        return new self(new SplFileInfo($path));
    }

    /**
     * CachePath constructor.
     * @param SplFileInfo $cacheFile
     */
    public function __construct(SplFileInfo $cacheFile)
    {
        $this->cacheFile = $cacheFile;
    }

    /**
     * @return SplFileObject
     */
    public function open(): SplFileObject
    {
        return $this->cacheFile->openFile(static::OPEN_MODE);
    }

    /**
     * @return object|null
     */
    public function read(): ?object
    {
        if ($this->isValid()) {
            return include $this->getFile();
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getFile(): string
    {
        return $this->cacheFile->getPathname();
    }

    /**
     * @return string
     */
    public function getDir(): string
    {
        return $this->cacheFile->getPath();
    }


    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->cacheFile->isFile();
    }


    /**
     * @return bool
     */
    private function isValid(): bool
    {
        return $this->exists() && $this->cacheFile->isReadable();
    }
}
