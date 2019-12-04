<?php

declare(strict_types=1);

namespace App\Kernel\Booter\ContainerLoader\ErrorHandler;

use App\Deprecation\DeprecationsCollection;
use App\Kernel\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler\BacktraceCleaner;
use App\Kernel\Booter\ContainerLoader\ErrorHandler\DeprecationsHandler\BacktraceCleanerInterface;
use function restore_error_handler;
use function set_error_handler;

final class DeprecationsHandler
{
    /**
     * @var callable
     */
    private $previousErrorHandler;

    /**
     * @var BacktraceCleanerInterface
     */
    private BacktraceCleanerInterface $backtraceCleaner;
    /**
     * @var DeprecationsCollection
     */
    private DeprecationsCollection $deprecations;

    /**
     * @param DeprecationsCollection|null $deprecations
     * @param BacktraceCleanerInterface|null $backtraceCleaner
     * @return DeprecationsHandler
     */
    public static function create(
        DeprecationsCollection $deprecations = null,
        BacktraceCleanerInterface $backtraceCleaner = null
    ): self {
        $deprecations = $deprecations ?? new DeprecationsCollection();
        $backtraceCleaner = $backtraceCleaner ?? new BacktraceCleaner();

        return new self($deprecations, $backtraceCleaner);
    }

    /**
     * DeprecationsHandler constructor.
     * @param DeprecationsCollection $deprecations
     * @param BacktraceCleanerInterface $backtraceCleaner
     */
    private function __construct(
        DeprecationsCollection $deprecations,
        BacktraceCleanerInterface $backtraceCleaner
    ) {
        $this->deprecations = $deprecations;
        $this->backtraceCleaner = $backtraceCleaner;
    }

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

        $this->deprecations->add($type, $message, $file, $line, [$this->backtraceCleaner->clean($line, $file)]);

        return null;
    }

    /**
     * @return array
     */
    public function getDeprecations(): iterable
    {
        yield from $this->deprecations->getDeprecations();
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
    public function restore(): iterable
    {
        restore_error_handler();

        return $this->getDeprecations();
    }
}
