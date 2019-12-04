<?php

declare(strict_types=1);

namespace Tests\Unit\App\Deprecation;

use App\Deprecation\Deprecation;
use App\Deprecation\Exception\ImmutablePropertyModification;
use App\Deprecation\Exception\ImmutablePropertyUnset;
use App\Deprecation\Exception\TypeNotDeprecation;
use ArrayAccess;
use PHPUnit\Framework\TestCase;

final class DeprecationTest extends TestCase
{
    /**
     * @test
     */
    public function itThrowsAnExceptionIfTheTypeIsNotDeprecated(): void
    {
        $this->expectException(TypeNotDeprecation::class);

        $msg = 'foo';
        $file = 'bar';
        $line = 4;
        $trace = ['baz'];

        $error = [
            'type' => E_NOTICE,
            'message' => $msg,
            'file' => $file,
            'line' => $line,
            'trace' => $trace,
        ];

        Deprecation::fromArray($error);
    }

    /**
     * @test
     */
    public function itBehavesLikeAnArray(): void
    {
        $msg = 'foo';
        $file = 'bar';
        $line = 4;
        $trace = ['baz'];

        $error = [
            'type' => E_DEPRECATED,
            'message' => $msg,
            'file' => $file,
            'line' => $line,
            'trace' => $trace,
        ];

        $deprecation = Deprecation::fromArray($error);

        $this->assertInstanceOf(ArrayAccess::class, $deprecation);

        $this->assertSame($line, $deprecation[Deprecation::KEY_LINE]);
        $this->assertSame($msg, $deprecation[Deprecation::KEY_MESSAGE]);
        $this->assertSame($file, $deprecation[Deprecation::KEY_FILE]);
        $this->assertSame(E_DEPRECATED, $deprecation[Deprecation::KEY_TYPE]);
        $this->assertSame($trace, $deprecation[Deprecation::KEY_TRACE]);
        $this->assertSame(1, $deprecation[Deprecation::KEY_COUNT]);

        $this->expectException(ImmutablePropertyModification::class);
        $deprecation->offsetSet(Deprecation::KEY_TYPE, E_WARNING);
    }

    /**
     * @test
     */
    public function itCannotBeUnset(): void
    {
        $msg = 'foo';
        $file = 'bar';
        $line = 4;
        $trace = ['baz'];

        $error = [
            'type' => E_DEPRECATED,
            'message' => $msg,
            'file' => $file,
            'line' => $line,
            'trace' => $trace,
        ];

        $deprecation = Deprecation::fromArray($error);

        $this->expectException(ImmutablePropertyUnset::class);
        $deprecation->offsetUnset(Deprecation::KEY_TRACE);
    }

    /**
     * @test
     */
    public function itReturnsANewSelfWithTheCount(): void
    {
        $msg = 'foo';
        $file = 'bar';
        $line = 4;
        $trace = ['baz'];

        $error = [
            'type' => E_DEPRECATED,
            'message' => $msg,
            'file' => $file,
            'line' => $line,
            'trace' => $trace,
        ];

        $deprecation = Deprecation::fromArray($error);

        $deprecation = $deprecation->increment();

        $this->assertInstanceOf(Deprecation::class, $deprecation);
        $this->assertSame(2, $deprecation[Deprecation::KEY_COUNT]);
    }

    /**
     * @test
     */
    public function itReturnsAnArray(): void
    {
        $msg = 'bar';
        $error = [
            'type' => E_DEPRECATED,
            'message' => $msg,
            'file' => 'bar',
            'line' => 4,
            'trace' => ['baz'],
            'count' => 1,
        ];
        $deprecation = Deprecation::fromArray($error);

        $result = $deprecation->toArray();

        $this->assertSame($error, $result);
    }
}