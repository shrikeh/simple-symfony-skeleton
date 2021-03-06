<?php

namespace Shrikeh\TestSymfonyApp\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler;

interface BacktraceCleanerInterface
{
    /**
     * @param int $line
     * @param string $file
     * @return array
     */
    public function clean(int $line, string $file): array;
}
