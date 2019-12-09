<?php

declare(strict_types=1);

namespace App\Deprecation;

use Ds\Map;
use Generator;

final class DeprecationsCollection
{
    /** var Map **/
    private Map $deprecations;

    /**
     * DeprecationsCollection constructor.
     */
    public function __construct()
    {
        $this->deprecations = new Map();
    }

    /**
     * @return Generator
     */
    public function getDeprecations(): Generator
    {
        yield from $this->deprecations;
    }

    /**
     * @param int $type
     * @param string $msg
     * @param string $file
     * @param int $line
     * @param array $trace
     * @return Deprecation
     */
    public function add(int $type, string $msg, string $file, int $line, array $trace): Deprecation
    {
        if ($this->deprecations->hasKey($msg)) {
            return $this->incrementDeprecation($msg);
        }
        return $this->addDeprecation(
            $this->createDeprecation($type, $msg, $file, $line, $trace),
            $msg
        );
    }

    /**
     * @param Deprecation $deprecation
     * @param string $msg
     * @return Deprecation
     */
    private function addDeprecation(Deprecation $deprecation, string $msg): Deprecation
    {
        $this->deprecations->put($msg, $deprecation);

        return $deprecation;
    }

    /**
     * @param string $msg
     * @return Deprecation
     */
    private function incrementDeprecation(string $msg): Deprecation
    {
        /** @var Deprecation $deprecation */
        $deprecation = $this->deprecations->get($msg);
        $deprecation = $deprecation->increment();

        $this->deprecations->put($msg, $deprecation);

        return $deprecation;
    }

    /**
     * @param int $type
     * @param string $msg
     * @param string $file
     * @param int $line
     * @param array $trace
     * @return Deprecation
     */
    private function createDeprecation(int $type, string $msg, string $file, int $line, array $trace): Deprecation
    {
        return Deprecation::fromArray([
            Deprecation::KEY_TYPE => $type,
            Deprecation::KEY_MESSAGE => $msg,
            Deprecation::KEY_FILE => $file,
            Deprecation::KEY_LINE => $line,
            Deprecation::KEY_TRACE => $trace,
        ]);
    }
}
