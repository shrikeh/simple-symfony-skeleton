<?php

declare(strict_types=1);

namespace Tests\Unit\App\Deprecation;

use App\Deprecation\Deprecation;
use App\Deprecation\DeprecationsCollection;
use PHPUnit\Framework\TestCase;

final class DeprecationsCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsDeprecations(): void
    {
        $msg = 'foo';
        $file = 'bar';
        $line = 4;
        $trace = ['baz'];

        $deprecations = new DeprecationsCollection();

        $deprecationCreated = $deprecations->add(E_USER_DEPRECATED, $msg, $file, $line, $trace);

        foreach ($deprecations->getDeprecations() as $key => $deprecation) {
            $this->assertSame($msg, $key);
            $this->assertSame($deprecationCreated, $deprecation);
            $this->assertSame($msg, $deprecation[Deprecation::KEY_MESSAGE]);
            $this->assertSame($file, $deprecation[Deprecation::KEY_FILE]);
            $this->assertSame($line, $deprecation[Deprecation::KEY_LINE]);
            $this->assertSame($trace, $deprecation[Deprecation::KEY_TRACE]);
            $this->assertSame(1,  $deprecation[Deprecation::KEY_COUNT]);
        }
    }

    /**
     * @test
     */
    public function itAddsToTheCount(): void
    {
        $msg = 'foo';
        $file = 'bar';
        $line = 4;
        $trace = ['baz'];

        $deprecations = new DeprecationsCollection();

        $deprecations->add(E_USER_DEPRECATED, $msg, $file, $line, $trace);
        $deprecations->add(E_USER_DEPRECATED, $msg, $file, $line, $trace);

        $deprecations->add(E_DEPRECATED, 'bar', $file, $line, $trace);
        $deprecations->add(E_USER_DEPRECATED, $msg, $file, $line, $trace);

        $deprecationsArray = iterator_to_array($deprecations->getDeprecations());

        $this->assertCount(2, $deprecationsArray);
        $first = $deprecationsArray[$msg];
        $this->assertSame(3, $first[Deprecation::KEY_COUNT]);
    }
}