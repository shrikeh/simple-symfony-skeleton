<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTest\Service;

use SplFileInfo;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\Service\Pathalizer\PathalizerInterface;
use Tests\Utils\UnitTest\Service\NamespaceMapper\NamespaceMapper;
use Tests\Utils\UnitTest\TestCase;

final class TestCaseGenerator
{
    /**
     * @var NamespaceMapper
     */
    private NamespaceMapper $namespaceMapper;
    /**
     * @var PathalizerInterface
     */
    private PathalizerInterface $pathalizer;

    /**
     * TestCaseGenerator constructor.
     * @param PathalizerInterface $pathalizer
     * @param NamespaceMapper $namespaceMapper
     */
    public function __construct(
        PathalizerInterface $pathalizer,
        NamespaceMapper $namespaceMapper
    ) {
        $this->pathalizer = $pathalizer;
        $this->namespaceMapper = $namespaceMapper;
    }

    /**
     * @param string $subjectClass
     * @return TestCase
     */
    public function createTestFor(string $subjectClass): TestCase
    {
        $subjectClassFqn = ClassNamespace::fromNamespaceString($subjectClass);
        $unitTestFqn = $this->namespaceMapper->getTestCaseFqnFor($subjectClassFqn);

        return new TestCase(
            $this->getUnitTestPath($unitTestFqn),
            $unitTestFqn,
            $subjectClassFqn
        );
    }

    /**
     * @param $unitTestFqn
     * @return SplFileInfo
     */
    private function getUnitTestPath(ClassNamespace $unitTestFqn): SplFileInfo
    {
        $this->pathalizer->getUnitTestPathFor($unitTestFqn);
    }
}
