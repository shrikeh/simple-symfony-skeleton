<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTest\Service\NamespaceMapper;

use Tests\Utils\UnitTest\ClassNamespace;

interface NamespaceMapperInterface
{
    /**
     * @param ClassNamespace $subject
     * @return ClassNamespace
     */
    public function getTestCaseFqnFor(ClassNamespace $subject): ClassNamespace;
}