<?php

declare(strict_types=1);

namespace Tests\Utils\Matcher;

use PhpSpec\Exception\Example\MatcherException;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\TestCase;

final class MatchTestCase
{
    public const NAME = 'matchTestCase';

    /**
     * @param TestCase $testCase
     * @param string $subjectFqn
     * @param string $testCaseFqn
     * @param string $path
     * @return bool
     * @throws MatcherException
     */
    public function __invoke(
        TestCase $testCase,
        string $subjectFqn,
        string $testCaseFqn,
        string $path
    ): bool {
        $testCaseClassNs = $testCase->getTestCaseFqn();
        if (!$this->matchFqn($testCaseClassNs, $testCaseFqn)) {
            throw new MatcherException(sprintf(
                'Test case FQN "%s" does not match expected "%s"',
                $testCaseClassNs->toString(),
                $testCaseFqn
            ));
        }
        $subjectClassNs = $testCase->getSubjectFqn();
        if (!$this->matchFqn($subjectClassNs, $subjectFqn)) {
            throw new MatcherException(sprintf(
                'Subject FQN "%s" does not match expected "%s"',
                $subjectClassNs->toString(),
                $subjectFqn
            ));
        }

        $fileInfo = $testCase->getFileInfo();

        if ($fileInfo->getPathname() !== $path) {
            throw new MatcherException(sprintf(
                'Test case path "%s" does not match expected value "%s"',
                $fileInfo->getPathname(),
                $path
            ));
        }

        return true;
    }

    /**
     * @param ClassNamespace $actual
     * @param string $expected
     * @return bool
     */
    private function matchFqn(ClassNamespace $actual, string $expected): bool
    {
        return $actual->toString() === $expected;
    }
}
