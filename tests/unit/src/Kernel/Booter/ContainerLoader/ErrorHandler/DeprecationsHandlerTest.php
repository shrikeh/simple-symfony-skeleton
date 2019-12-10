<?php

declare(strict_types=1);

namespace Tests\Unit\App\Kernel\Booter\ContainerLoader\ErrorHandler;

use App\Deprecation\DeprecationsCollection;
use App\Kernel\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler;
use App\Kernel\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler\BacktraceCleaner;
use App\Kernel\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler\BacktraceCleanerInterface;
use PHPUnit\Framework\TestCase;

use function restore_error_handler;
use function set_error_handler;

final class DeprecationsHandlerTest extends TestCase
{
    /** @var callable */
    private $errorHandler;

    /**
     * @test
     */
    public function itRegistersItselfAsAnErrorHandler(): void
    {
        $errorHandler = DeprecationsHandler::create();
        $errorHandler->register();

        $this->assertSame($errorHandler, $this->getCurrentErrorHandler());
    }

    /**
     * @test
     */
    public function itRestoresItselfAsAnErrorHandler(): void
    {
        $errorHandler = DeprecationsHandler::create();
        $errorHandler->register();
        $errorHandler->restore();
        $this->assertNotSame($errorHandler, $this->getCurrentErrorHandler());
    }

    /**
     * @test
     */
    public function itAddsAMessageToTheLog(): void
    {
        $msg = 'foo';
        $error = [
            'type' => E_USER_DEPRECATED,
            'message' => $msg,
            'file' => 'bar',
            'line' => 4,
            'trace' => 'baz',
            'count' => 1,
        ];

        $deprecationsCollection = new DeprecationsCollection();
        $backtraceCleaner = $this->prophesize(BacktraceCleanerInterface::class);

        $backtraceCleaner->clean(4, 'bar')->shouldBeCalled();
        $errorHandler = DeprecationsHandler::create(
            $deprecationsCollection,
            $backtraceCleaner->reveal()
        );


        $this->assertNull(call_user_func_array($errorHandler, $error));

        $recordedErrors = iterator_to_array($deprecationsCollection->getDeprecations());

        $this->assertArrayHasKey($msg, $recordedErrors);
        $recordedError = $recordedErrors[$msg];

        foreach (['type', 'message', 'file', 'line', 'count'] as $key) {
            $this->assertSame($error[$key], $recordedError[$key]);
        }
    }

    /**
     * @test
     */
    public function itDoesNotRegisterNonDeprecationErrors(): void
    {
        $errorHandler = DeprecationsHandler::create();

        $msg = 'foo';
        $error = [
            'type' => E_WARNING,
            'message' => $msg,
            'file' => 'bar',
            'line' => 4,
            'trace' => 'baz',
            'count' => 1,
        ];
        $this->assertFalse(call_user_func_array($errorHandler, $error));

        $this->assertArrayNotHasKey($msg, iterator_to_array($errorHandler->getDeprecations()));
    }

    /**
     * Grab the current error handler to restore after this test case has completed.
     */
    protected function setUp(): void
    {
        $this->errorHandler = $this->getCurrentErrorHandler();
    }

    /**
     * Restore the previous error handler
     */
    protected function tearDown(): void
    {
        set_error_handler($this->errorHandler);
    }

    /**
     * @return callable
     */
    private function getCurrentErrorHandler(): callable
    {
        $errorHandler = set_error_handler(static function () {
        });
        restore_error_handler();

        return $errorHandler;
    }
}
