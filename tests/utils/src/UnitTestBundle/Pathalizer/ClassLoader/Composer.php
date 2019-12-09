<?php
declare(strict_types=1);

namespace Tests\Utils\Service\UnitTests\Pathalizer\ClassLoader;

use Composer\Autoload\ClassLoader;
use SplFileInfo;
use Tests\Utils\Service\UnitTests\Pathalizer\FqnPath;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\NamespaceCollection;

final class Composer implements ClassLoaderInterface
{
    /**
     * @var ClassLoader
     */
    private ClassLoader $classLoader;

    /**
     * Composer constructor.
     * @param ClassLoader $classLoader
     */
    public function __construct(ClassLoader $classLoader)
    {
        $this->classLoader = $classLoader;
    }

    /**
     * {@inheritDoc}
     */
    public function getFqnPathForClass(ClassNamespace $testCaseFqn): FqnPath
    {
        $namespaces = $this->getPsr4NamespaceCollection();
        $baseFqn = $namespaces->match($testCaseFqn);
        $baseDir = new SplFileInfo($namespaces->getMetadataFor($baseFqn));

        return  new FqnPath(
            $baseFqn,
            $baseDir
        );
    }

    /**
     * @return NamespaceCollection
     */
    private function getPsr4NamespaceCollection(): NamespaceCollection
    {
        return NamespaceCollection::fromArray($this->getPsr4PrefixPaths());
    }

    /**
     * @return iterable
     */
    private function getPsr4PrefixPaths(): iterable
    {
        foreach ($this->classLoader->getPrefixesPsr4() as $namespace => $paths) {
            yield $namespace => $paths[0];
        }
    }
}
