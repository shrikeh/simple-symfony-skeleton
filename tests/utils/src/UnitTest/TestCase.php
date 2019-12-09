<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTest;

use SplFileInfo;

final class TestCase
{
    /** @var SplFileInfo  */
    private SplFileInfo $fileInfo;

    /** @var ClassNamespace  */
    private ClassNamespace $fullyQualifiedName;

    /** @var ClassNamespace  */
    private ClassNamespace $subjectUnderTest;

    /**
     * TestCase constructor.
     * @param SplFileInfo $fileInfo
     * @param ClassNamespace $fullyQualifiedName
     * @param ClassNamespace $subjectUnderTest
     */
    public function __construct(
        SplFileInfo $fileInfo,
        ClassNamespace $fullyQualifiedName,
        ClassNamespace $subjectUnderTest
    ) {
        $this->fileInfo = $fileInfo;
        $this->fullyQualifiedName = $fullyQualifiedName;
        $this->subjectUnderTest = $subjectUnderTest;
    }

    /**
     * @return SplFileInfo
     */
    public function getFileInfo(): SplFileInfo
    {
        return $this->fileInfo;
    }

    /**
     * @return ClassNamespace
     */
    public function getTestCaseFqn(): ClassNamespace
    {
        return $this->fullyQualifiedName;
    }

    /**
     * @return ClassNamespace
     */
    public function getSubjectFqn(): ClassNamespace
    {
        return $this->subjectUnderTest;
    }
}