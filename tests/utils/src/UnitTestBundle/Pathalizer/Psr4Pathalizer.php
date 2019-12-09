<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTestBundle\Pathalizer;

use SplFileInfo;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\Service\Pathalizer\PathalizerInterface;

use function preg_quote;
use function preg_replace;

use Tests\Utils\UnitTestBundle\Pathalizer\ClassLoader\ClassLoaderInterface;

final class Psr4Pathalizer implements PathalizerInterface
{
    /**
     * @var ClassLoaderInterface
     */
    private ClassLoaderInterface $classLoader;

    /**
     * @return string
     */
    private static function getDirRegex(): string
    {
        return sprintf('|%s{2,}|', preg_quote(DIRECTORY_SEPARATOR, '|'));
    }

    /**
     * Psr4Pathalizer constructor.
     * @param ClassLoaderInterface $classLoader
     */
    public function __construct(ClassLoaderInterface $classLoader)
    {
        $this->classLoader = $classLoader;
    }

    /**
     * @param ClassNamespace $testCaseFqn
     * @return SplFileInfo
     */
    public function getUnitTestPathFor(ClassNamespace $testCaseFqn): SplFileInfo
    {
        $path =  preg_replace(
            self::getDirRegex(),
            DIRECTORY_SEPARATOR,
            $this->getPath($testCaseFqn)
        );

        return new SplFileInfo($path);
    }

    /**
     * @param ClassNamespace $testCaseFqn
     * @return string
     */
    private function getPath(ClassNamespace $testCaseFqn): string
    {
        $testFqnPath = $this->classLoader->getFqnPathForClass($testCaseFqn);

        $relativeTestCaseFqn = $testCaseFqn->getRelativeNamespaceTo(
            $testFqnPath->getBaseFqn()
        );

        return sprintf(
            '%s/%s.php',
            $testFqnPath->getBaseDir()->getRealPath(),
            $this->relativeFqnToPath($relativeTestCaseFqn)
        );
    }

    /**
     * @param ClassNamespace $relativeFqn
     * @return string
     */
    private function relativeFqnToPath(ClassNamespace $relativeFqn): string
    {
        return implode(DIRECTORY_SEPARATOR, $relativeFqn->getNamespaceParts());
    }
}
