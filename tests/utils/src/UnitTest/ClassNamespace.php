<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTest;

final class ClassNamespace
{
    /** @var string  */
    public const NAMESPACE_SEPARATOR = '\\';

    /** @var array */
    private array $namespaceParts;

    /**
     * @param string $namespace
     * @return ClassNamespace
     */
    public static function fromNamespaceString(string $namespace): self
    {
        $namespace = str_replace(DIRECTORY_SEPARATOR, self::NAMESPACE_SEPARATOR, $namespace);

        return new self(explode(self::NAMESPACE_SEPARATOR, $namespace));
    }

    /**
     * ClassNamespace constructor.
     * @param array $namespaceParts
     */
    public function __construct(array $namespaceParts)
    {
        $this->namespaceParts = array_values(array_filter($namespaceParts));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function getLastPart(): string {
        return end($this->namespaceParts);
    }

    /**
     * @return ClassNamespace
     */
    public function getParent(): self
    {
        $parts = $this->getNamespaceParts();
        array_pop($parts);

        return new self($parts);
    }

    /**
     * @param ClassNamespace $parent
     * @return bool
     */
    public function isChildOf(ClassNamespace $parent): bool
    {
        // append a namespace separator to ensure it is a child
        $parentNamespace = $parent->toString() . self::NAMESPACE_SEPARATOR;

        return (0 === strpos($this->toString(), $parentNamespace));
    }

    /**
     * @param ClassNamespace $classNamespace
     * @return ClassNamespace
     */
    public function prependTo(ClassNamespace $classNamespace): ClassNamespace
    {
        return $classNamespace->appendTo($this);
    }

    /**
     * @param ClassNamespace $classNamespace
     * @return ClassNamespace
     */
    public function appendTo(ClassNamespace $classNamespace): ClassNamespace
    {
        return new self(array_merge($classNamespace->getNamespaceParts(), $this->getNamespaceParts()));
    }

    /**
     * @param ClassNamespace $classNamespace
     * @return ClassNamespace
     */
    public function getRelativeNamespaceTo(ClassNamespace $classNamespace): ClassNamespace
    {
        $namespaceParts = $classNamespace->getNamespaceParts();
        $classNameParts = $this->getNamespaceParts();

        $length = count($namespaceParts);
        for ($i=0; $i < $length; $i++) {
            $namespacePart = $namespaceParts[$i];

            if ($namespacePart === $classNameParts[$i]) {
                $classNameParts[$i] = null;
            }
        }

        return new self($classNameParts);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return  implode(self::NAMESPACE_SEPARATOR, $this->namespaceParts);
    }

    /**
     * @return array
     */
    public function getNamespaceParts(): array
    {
        return $this->namespaceParts;
    }
}