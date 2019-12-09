<?php

declare(strict_types=1);

namespace spec\Tests\Utils\UnitTest;

use PhpSpec\ObjectBehavior;
use SplFileInfo;
use Tests\Utils\UnitTest\ClassNamespace;

final class TestCaseSpec extends ObjectBehavior
{
    public function it_returns_the_test_case_fqn(): void
    {
        $splFileInfo = new SplFileInfo(__FILE__);
        $testFqn = ClassNamespace::fromNamespaceString('Foo\Bar\BazTest');
        $subjectFqn = ClassNamespace::fromNamespaceString('Foo\UnitTest');
        $this->beConstructedWith(
            $splFileInfo,
            $testFqn,
            $subjectFqn
        );

        $this->getTestCaseFqn()->shouldReturn($testFqn);
    }

    public function it_returns_the_test_case_path_info(): void
    {
        $splFileInfo = new SplFileInfo(__FILE__);
        $this->beConstructedWith(
            $splFileInfo,
            ClassNamespace::fromNamespaceString('Foo'),
            ClassNamespace::fromNamespaceString('Bar')
        );

        $this->getFileInfo()->shouldReturn($splFileInfo);
    }

    public function it_returns_the_subject_fqn(): void
    {
        $splFileInfo = new SplFileInfo(__FILE__);
        $testFqn = ClassNamespace::fromNamespaceString('Foo\Bar\BazTest');
        $subjectFqn = ClassNamespace::fromNamespaceString('Foo\UnitTest');
        $this->beConstructedWith(
            $splFileInfo,
            $testFqn,
            $subjectFqn
        );

        $this->getSubjectFqn()->shouldReturn($subjectFqn);
    }
}
