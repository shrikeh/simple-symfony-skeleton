<?php

declare(strict_types=1);

namespace Tests\Utils\TestCaseBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class TestCaseBundle extends Bundle
{
    public const BUNDLE_DIR = __DIR__;

    public const DEFAULT_TEMPLATE_DIR = self::BUNDLE_DIR . '/Resources/templates';
}
