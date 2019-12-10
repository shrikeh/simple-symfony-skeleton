<?php

declare(strict_types=1);

use App\Kernel\Environment\EnvironmentInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Tests\Utils\TestCaseBundle\TestCaseBundle;

return [
    FrameworkBundle::class => ['all' => true],
    TestCaseBundle::class => [EnvironmentInterface::ENV_DEV => true],
];
