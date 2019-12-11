<?php

declare(strict_types=1);

namespace Tests\Unit\TestSymfonyApp\Kernel\Booter\Exception;

use Shrikeh\TestSymfonyApp\Kernel\Booter\Exception\ContainerFetchedWhileUnbooted;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ContainerFetchedWhileUnbootedTest extends TestCase
{
    /**
     * @test
     */
    public function itIsARuntimeException(): void
    {
        $this->assertInstanceOf(RuntimeException::class, ContainerFetchedWhileUnbooted::create());
    }
}
