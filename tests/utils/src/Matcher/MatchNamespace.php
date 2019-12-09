<?php

declare(strict_types=1);

namespace Tests\Utils\Matcher;

use Tests\Utils\UnitTest\ClassNamespace;

final class MatchNamespace
{
    public const NAME = 'matchNamespace';

    /**
     * @param ClassNamespace $namespace
     * @param string $expected
     * @return bool
     */
    public function __invoke(ClassNamespace $namespace, string $expected): bool
    {
        return $namespace->toString() === $expected;
    }
}
