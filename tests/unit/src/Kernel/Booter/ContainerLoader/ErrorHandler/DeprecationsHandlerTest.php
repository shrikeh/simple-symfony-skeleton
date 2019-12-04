<?php

declare(strict_types=1);

namespace Tests\Unit\App\Kernel\Booter\ContainerLoader\ErrorHandler;

use App\Kernel\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler;
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
        $errorHandler = new DeprecationsHandler();
        $errorHandler->register();

        $this->assertSame($errorHandler, $this->getCurrentErrorHandler());
    }

    /**
     * @test
     */
    public function itRestoresItselfAsAnErrorHandler(): void
    {
        $errorHandler = new DeprecationsHandler();
        $errorHandler->register();
        $errorHandler->restore();
        $this->assertNotSame($errorHandler, $this->getCurrentErrorHandler());
    }

    /**
     * @test
     */
    public function itAddsAMessageToTheLog(): void
    {
        $errorHandler = new DeprecationsHandler();

        $msg = 'foo';
        $error = [
            'type' => E_USER_DEPRECATED,
            'message' => $msg,
            'file' => 'bar',
            'line' => 4,
            'trace' => 'baz',
            'count' => 1,
        ];
        call_user_func_array($errorHandler, $error);

        $recordedErrors = $errorHandler->getLogs();

        $this->assertArrayHasKey($msg, $recordedErrors);
        $recordedError = $recordedErrors[$msg];

        foreach(['type', 'message', 'file', 'line', 'count'] as $key) {
            $this->assertSame($error[$key], $recordedError[$key]);
        }
        $backtrace = $recordedError['trace'][0];
        $this->assertSame(TestCase::class, $backtrace['class']);
    }

    /**
     * @test
     */
    public function itDoesNotRegisterNonDeprecationErrors(): void
    {
        $errorHandler = new DeprecationsHandler();

        $msg = 'foo';
        $error = [
            'type' => E_WARNING,
            'message' => $msg,
            'file' => 'bar',
            'line' => 4,
            'trace' => 'baz',
            'count' => 1,
        ];
        call_user_func_array($errorHandler, $error);

        $this->assertArrayNotHasKey($msg, $errorHandler->getLogs());
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
        $errorHandler = set_error_handler(static function(){});
        restore_error_handler();

        return $errorHandler;
    }

}