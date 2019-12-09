<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTest\NamespaceCollection;

use Tests\Utils\UnitTest\ClassNamespace;
use function strlen;

final class NamespaceSort
{
    /**
     * @param ClassNamespace $a
     * @param ClassNamespace $b
     * @return int
     */
    public function __invoke(ClassNamespace $a, ClassNamespace $b): int
    {
        return strlen($b->toString()) <=> strlen($a->toString());
    }
}