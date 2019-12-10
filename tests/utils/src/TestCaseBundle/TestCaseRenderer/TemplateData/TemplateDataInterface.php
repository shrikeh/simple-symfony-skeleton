<?php

declare(strict_types=1);

namespace Tests\Utils\TestCaseBundle\TestCaseRenderer\TemplateData;

use Tests\Utils\UnitTest\TestCase;

interface TemplateDataInterface
{
    /**
     * @param TestCase $testCase
     * @return array
     */
    public function getTemplateData(TestCase $testCase): iterable;
}
