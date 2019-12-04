<?php

declare(strict_types=1);

namespace App\Kernel\Booter\ContainerLoader\ErrorHandler;

use Symfony\Component\ErrorHandler\DebugClassLoader;
use function array_slice;
use function count;
use function debug_backtrace;
use function restore_error_handler;
use function set_error_handler;

final class DeprecationsHandler
{
    /**
     * @var callable
     */
    private $previousErrorHandler;

    /**
     * @var array
     */
    private array $collectedLogs = [];

    /**
     * @param int $type
     * @param string $message
     * @param string $file
     * @param int $line
     * @return bool|null
     */
    public function __invoke(int $type, string $message, string $file, int $line): ?bool
    {
        if (E_USER_DEPRECATED !== $type && E_DEPRECATED !== $type) {
            $previousHandler = $this->previousErrorHandler;
            return $previousHandler ? $previousHandler($type, $message, $file, $line) : false;
        }

        if ($this->addMessageToLog($message)) {
            return null;
        }

        $this->collectedLogs[$message] = [
            'type' => $type,
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'trace' => [$this->cleanBacktrace($line, $file)],
            'count' => 1,
        ];

        return null;
    }

    /**
     * @return array
     */
    public function getLogs(): array
    {
        return $this->collectedLogs;
    }

    /**
     * Register this as the error handler
     */
    public function register(): void
    {
        $previousHandler = set_error_handler($this);
        $this->previousErrorHandler = $previousHandler;
    }

    /**
     * @return array
     */
    public function restore(): array
    {
        restore_error_handler();

        return $this->collectedLogs;
    }
    /**
     * @param int $line
     * @param string $file
     * @return mixed
     */
    private function cleanBacktrace(int $line, string $file)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        // Clean the trace by removing first frames added by the error handler itself.
        for ($i = 0; isset($backtrace[$i]); ++$i) {
            if ($this->isErrorHandlerFrame($backtrace[$i], $line, $file)) {
                $backtrace = array_slice($backtrace, 1 + $i);
                break;
            }
        }
        // Remove frames added by DebugClassLoader.
        for ($i = count($backtrace) - 2; 0 < $i; --$i) {
            if ($backtrace[$i]['class'] ?? null === DebugClassLoader::class) {
                $backtrace = [$backtrace[$i + 1]];
                break;
            }
        }

        return $backtrace[0];
    }

    /**
     * @param string $message
     * @return bool|null
     */
    private function addMessageToLog(string $message): bool
    {
        if (isset($this->collectedLogs[$message])) {
            ++$this->collectedLogs[$message]['count'];

            return true;
        }

        return false;
    }

    /**
     * @param array $frame
     * @param int $line
     * @param string $file
     * @return bool
     */
    private function isErrorHandlerFrame(array $frame, int $line, string $file): bool
    {
        return (isset($frame['file'], $frame['line']) && $frame['line'] === $line && $frame['file'] === $file);
    }
}
