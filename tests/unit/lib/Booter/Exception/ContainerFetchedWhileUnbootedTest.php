<?php

declare(strict_types=1);

namespace Tests\Unit\TestSymfonyApp\Booter\Exception;

use Shrikeh\TestSymfonyApp\Booter\Exception\ContainerFetchedWhileUnbooted;
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
