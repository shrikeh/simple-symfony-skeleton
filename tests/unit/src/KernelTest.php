<?php

declare(strict_types=1);

namespace Tests\Unit\App;

use App\Kernel;
use App\Kernel\Exception\UnrecognisedEnvironment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class KernelTest extends KernelTestCase
{
    public function testItThrowsAnExceptionIfTheEnvironmentIsUnrecognised()
    {
        $this->expectException(UnrecognisedEnvironment::class);
        $kernel = new Kernel('foo', false);
    }
}
