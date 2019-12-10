<?php

declare(strict_types=1);

namespace Tests\Utils\TestCaseBundle\TestCaseRenderer;

use Tests\Utils\UnitTest\TestCase;

interface TestCaseRendererInterface
{
    /**
     * @param TestCase $testCase
     * @return string
     */
    public function render(TestCase $testCase): string;
}
