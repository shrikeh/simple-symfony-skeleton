<?php

declare(strict_types=1);

namespace Tests\Utils\UnitTestBundle\TestCaseRenderer\TemplateData;

use Tests\Utils\UnitTest\TestCase;

final class SimpleTemplateData implements TemplateDataInterface
{
    /**
     * @var array
     */
    private array $data;

    /**
     * SimpleTemplateData constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param TestCase $testCase
     * @return iterable
     */
    public function getTemplateData(TestCase $testCase): iterable
    {
        $testCaseData = $this->getTestCaseTemplateData($testCase);

        return $this->addTemplateData($testCaseData);
    }

    /**
     * @param array $testCaseData
     * @return array
     */
    private function addTemplateData(array $testCaseData): iterable
    {
        return array_merge($testCaseData, $this->data);
    }

    /**
     * @param TestCase $testCase
     * @return array
     */
    private function getTestCaseTemplateData(TestCase $testCase): array
    {
        $testCaseFqn = $testCase->getTestCaseFqn();
        $subjectFqn = $testCase->getSubjectFqn();

        return [
            'test_case' => [
                'namespace' => $testCaseFqn->getParent()->toString(),
                'class_name' => $testCaseFqn->getLastPart(),
                'subject' => [
                    'fqn'   => $subjectFqn->toString(),
                    'class_name' => $subjectFqn->getLastPart(),
                ],
            ],
        ];
    }
}
