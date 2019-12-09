<?php

declare(strict_types=1);

use App\Kernel\Environment\EnvironmentInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Tests\Utils\UnitTestBundle\UnitTestBundle;

return [
    FrameworkBundle::class => ['all' => true],
    UnitTestBundle::class => [EnvironmentInterface::ENV_DEV => true],
];
