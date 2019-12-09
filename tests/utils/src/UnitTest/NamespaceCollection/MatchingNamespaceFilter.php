<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTest\NamespaceCollection;

use Tests\Utils\UnitTest\ClassNamespace;

final class MatchingNamespaceFilter
{
    /** @var ClassNamespace */
    private ClassNamespace $childFqn;

    /**
     * MatchingNamespaceFilter constructor.
     * @param ClassNamespace $childFqn
     */
    public function __construct(ClassNamespace $childFqn)
    {
        $this->childFqn = $childFqn;
    }

    /**
     * @param ClassNamespace $parentFqn
     * @param $metadata
     * @return bool
     */
    public function __invoke(ClassNamespace $parentFqn, $metadata): bool
    {
        return $this->childFqn->isChildOf($parentFqn);
    }
}