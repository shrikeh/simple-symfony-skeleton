<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTest\NamespaceCollection;

use Tests\Utils\UnitTest\ClassNamespace;

final class ChildNamespaceFilter
{
    /** @var ClassNamespace */
    private ClassNamespace $childFqn;

    /**
     * ChildNamespaceFilter constructor.
     * @param ClassNamespace $childFqn
     */
    public function __construct(ClassNamespace $childFqn)
    {
        $this->childFqn = $childFqn;
    }

    /**
     * @param ClassNamespace $parentFqn
     * @return bool
     */
    public function __invoke(ClassNamespace $parentFqn): bool
    {
        return $this->childFqn->isChildOf($parentFqn);
    }
}