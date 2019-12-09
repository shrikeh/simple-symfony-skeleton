<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTestBundle\Pathalizer\ClassLoader;

use Tests\Utils\UnitTestBundle\Pathalizer\FqnPath;
use Tests\Utils\UnitTest\ClassNamespace;

interface ClassLoaderInterface
{
    /**
     * @param ClassNamespace $className
     * @return FqnPath
     */
    public function getFqnPathForClass(ClassNamespace $className): FqnPath;
}
