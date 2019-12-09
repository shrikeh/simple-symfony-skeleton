<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTest;

use Ds\Map;
use Tests\Utils\UnitTest\NamespaceCollection\ChildNamespaceFilter;
use Tests\Utils\UnitTest\NamespaceCollection\NamespaceSort;

final class NamespaceCollection
{
    /** @var Map  */
    private Map $namespaces;

    public static function fromArray(iterable $data): self
    {
        $map = new Map();
        foreach ($data as $namespace => $metadata) {
            $map->put(
                ClassNamespace::fromNamespaceString($namespace),
                $metadata
            );
        }

        return new self($map);
    }

    /**
     * NamespaceCollection constructor.
     * @param iterable $data
     */
    public function __construct(iterable $data)
    {
        $this->namespaces = new Map();

        foreach ($data as $namespace => $metadata) {
            $this->addNamespace($namespace, $metadata);
        }
    }

    /**
     * @param ClassNamespace $namespace
     * @return ClassNamespace
     */
    public function match(ClassNamespace $namespace): ClassNamespace
    {
        return $this->getMatchingNamespaces($namespace)->first()->key;
    }


    /**
     * @param ClassNamespace $fqn
     * @return mixed
     */
    public function getMetadataFor(ClassNamespace $fqn)
    {
        return $this->namespaces->get($fqn);
    }

    /**
     * @param ClassNamespace $fqn
     * @return Map
     */
    private function getMatchingNamespaces(ClassNamespace $fqn): Map
    {
        $matches =  $this->namespaces->filter(new ChildNamespaceFilter($fqn));
        $matches->ksort(new NamespaceSort());

        return $matches;
    }

    /**
     * @param ClassNamespace $namespace
     * @param null $metadata
     */
    private function addNamespace(ClassNamespace $namespace, $metadata = null): void
    {
        $this->namespaces->put($namespace, $metadata);
    }
}
