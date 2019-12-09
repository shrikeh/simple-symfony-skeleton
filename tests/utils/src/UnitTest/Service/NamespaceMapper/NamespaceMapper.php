<?php
declare(strict_types=1);

namespace Tests\Utils\UnitTest\Service\NamespaceMapper;

use Ds\Map;
use Tests\Utils\UnitTest\ClassNamespace;

use Tests\Utils\UnitTest\NamespaceCollection;

final class NamespaceMapper
{
    /** @var NamespaceCollection  */
    private NamespaceCollection $namespaces;

    /**
     * @param array $namespaceMap
     * @return NamespaceMapper
     */
    public static function fromArray(array $namespaceMap): self
    {
        return new self(NamespaceCollection::fromArray($namespaceMap));
    }

    /**
     * NamespaceMapper constructor.
     * @param $namespaces
     */
    public function __construct(NamespaceCollection $namespaces)
    {
        $this->namespaces = $namespaces;
    }

    /**
     * @param ClassNamespace $className
     * @return ClassNamespace
     */
    public function getTestCaseFqnFor(ClassNamespace $className): ClassNamespace
    {
        $classBaseNamespace = $this->namespaces->match($className);

        $testBaseNamespace = ClassNamespace::fromNamespaceString(
            $this->namespaces->getMetadataFor($classBaseNamespace)
        );

        $relativeClassNamespace = $className->getRelativeNamespaceTo($classBaseNamespace);

        return $this->createFullClassTestNamespace($testBaseNamespace, $relativeClassNamespace);
    }

    /**
     * @param ClassNamespace $testNamespace
     * @param ClassNamespace $relativeClass
     * @return ClassNamespace
     */
    private function createFullClassTestNamespace(
        ClassNamespace $testNamespace,
        ClassNamespace $relativeClass
    ): ClassNamespace {
        $testClassRelativeNamespace = ClassNamespace::fromNamespaceString(
            $relativeClass->toString() . 'Test'
        );

        return $testClassRelativeNamespace->appendTo($testNamespace);
    }
}