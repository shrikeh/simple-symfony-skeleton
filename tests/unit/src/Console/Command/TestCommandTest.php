<?php
declare(strict_types=1);

use App\Kernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class TestCommandTest extends TestCase
{
    /**
     *
     */
    public function testCanBootKernel(): void
    {
        $kernel = new Kernel('test', false);
        $this->assertInstanceOf(BaseKernel::class, $kernel);
        $kernel->boot();
    }
}