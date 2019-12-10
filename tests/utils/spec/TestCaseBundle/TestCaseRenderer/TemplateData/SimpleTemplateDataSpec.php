<?php

declare(strict_types=1);

namespace spec\Tests\Utils\TestCaseBundle\TestCaseRenderer\TemplateData;

use SplFileInfo;
use Tests\Utils\UnitTest\ClassNamespace;
use Tests\Utils\UnitTest\TestCase;
use Tests\Utils\TestCaseBundle\TestCaseRenderer\TemplateData\SimpleTemplateData;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class SimpleTemplateDataSpec extends ObjectBehavior
{
    public function it_populates_an_array_with_data_from_the_test_case(): void
    {
        $testCaseFqn = 'Foo\BarTest';
        $subjectFqn = 'The\TestSubject';

        $testCase = new TestCase(
            new SplFileInfo(__FILE__),
            ClassNamespace::fromNamespaceString($testCaseFqn),
            ClassNamespace::fromNamespaceString($subjectFqn)
        );

        $data = [
            'author' => [
                'name' => 'Barney Hanlon',
                'email' => 'barney@shrikeh.net',
            ],
            'copyright_date' => '2019-12-09'
        ];

        $this->beConstructedWith($data);

        $this->getTemplateData($testCase)->shouldIterateAs([
            'test_case' => [
                'namespace' => 'Foo',
                'class_name' => 'BarTest',
                'subject' => [
                    'fqn'   => $subjectFqn,
                    'class_name' => 'TestSubject',
                ],
            ],
            'author' => [
                'name' => 'Barney Hanlon',
                'email' => 'barney@shrikeh.net',
            ],
            'copyright_date' => '2019-12-09',
        ]);
    }
}
