<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTestBundle\Pathalizer;

use SplFileInfo;
use Tests\Utils\UnitTest\ClassNamespace;

final class FqnPath
{
    /** @var ClassNamespace  */
    private ClassNamespace $baseFqn;

    /** @var SplFileInfo  */
    private SplFileInfo $baseDir;

    /**
     * @param string $baseFqn
     * @param string $path
     * @return FqnPath
     */
    public static function fromString(string $baseFqn, string $path): self
    {
        return new self(
            ClassNamespace::fromNamespaceString($baseFqn),
            new SplFileInfo($path)
        );
    }

    /**
     * FqnPath constructor.
     * @param $baseFqn
     * @param $baseDir
     */
    public function __construct(ClassNamespace $baseFqn, SplFileInfo $baseDir)
    {
        $this->baseFqn = $baseFqn;
        $this->baseDir = $baseDir;
    }

    /**
     * @return ClassNamespace
     */
    public function getBaseFqn(): ClassNamespace
    {
        return $this->baseFqn;
    }

    /**
     * @return SplFileInfo
     */
    public function getBaseDir(): SplFileInfo
    {
        return $this->baseDir;
    }
}
