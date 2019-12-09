<?php

declare(strict_types=1);

namespace Tests\Utils\Matcher;

use Tests\Utils\UnitTestBundle\Pathalizer\FqnPath;

final class MatchFqnPath
{
    public const NAME = 'matchFqnPath';

    /**
     * @param FqnPath $fqnPath
     * @param string $expectedNamespace
     * @param string $expectedDir
     * @return bool
     */
    public function __invoke(FqnPath $fqnPath, string $expectedNamespace, string $expectedDir): bool
    {
        if ($fqnPath->getBaseDir()->getPathname() === $expectedDir) {
            if ($fqnPath->getBaseFqn()->toString() === $expectedNamespace) {
                return true;
            }
        }

        return false;
    }
}
