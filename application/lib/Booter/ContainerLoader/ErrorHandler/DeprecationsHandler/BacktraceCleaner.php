<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler;

use Symfony\Component\ErrorHandler\DebugClassLoader;

use function array_slice;
use function count;
use function debug_backtrace;

final class BacktraceCleaner implements BacktraceCleanerInterface
{
    /**
     * @var int
     */
    private const DEBUG_LIMIT = 6;

    /**
     * @param int $line
     * @param string $file
     * @return array
     */
    public function clean(int $line, string $file): array
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, self::DEBUG_LIMIT);
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
