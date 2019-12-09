<?php

declare(strict_types=1);

namespace Tests\Utils\Matcher;

use PhpSpec\Exception\Example\MatcherException;
use SplFileInfo;

final class MatchSplFileInfoPath
{
    public const NAME = 'matchSplFilePath';

    /**
     * @param SplFileInfo $fileInfo
     * @param string $expectedPath
     * @return bool
     * @throws MatcherException
     */
    public function __invoke(SplFileInfo $fileInfo, string $expectedPath): bool
    {
        if ($fileInfo->getPathname() !== $expectedPath) {
            throw new MatcherException(sprintf(
                'Path "%s" does not match expected path "%s"',
                $fileInfo->getPathname(),
                $expectedPath
            ));
        }

        return true;
    }
}