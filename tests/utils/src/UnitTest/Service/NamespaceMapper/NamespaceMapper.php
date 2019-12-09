<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTest\Service\NamespaceMapper;

use Ds\Map;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\NamespaceCollection;

final class NamespaceMapper implements NamespaceMapperInterface
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
     * {@inheritDoc}
     */
    public function getTestCaseFqnFor(ClassNamespace $subject): ClassNamespace
    {
        $classBaseNamespace = $this->namespaces->match($subject);

        $testBaseNamespace = ClassNamespace::fromNamespaceString(
            $this->namespaces->getMetadataFor($classBaseNamespace)
        );

        $relativeClassNamespace = $subject->getRelativeNamespaceTo($classBaseNamespace);

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
