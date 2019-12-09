<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTest\Service\Pathalizer;

use SplFileInfo;
use Tests\Utils\UnitTest\ClassNamespace;

interface PathalizerInterface
{
    /**
     * Generate the full absolute path for where the Unit test should live.
     * @param ClassNamespace $testCase
     * @return SplFileInfo
     */
    public function getUnitTestPathFor(ClassNamespace $testCase): SplFileInfo;
}
