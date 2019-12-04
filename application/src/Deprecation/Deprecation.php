<?php

declare(strict_types=1);

namespace App\Deprecation;

use App\Deprecation\Exception\ImmutablePropertyModification;
use App\Deprecation\Exception\ImmutablePropertyUnset;
use App\Deprecation\Exception\TypeNotDeprecation;
use function array_key_exists;
use ArrayAccess;
use JsonSerializable;

final class Deprecation implements ArrayAccess, JsonSerializable
{
    public const KEY_TYPE = 'type';
    public const KEY_MESSAGE = 'message';
    public const KEY_FILE = 'file';
    public const KEY_LINE = 'line';
    public const KEY_TRACE = 'trace';
    public const KEY_COUNT = 'count';

    /**
     * @var int
     */
    private int $type;
    /**
     * @var string
     */
    private string $message;
    /**
     * @var string
     */
    private string $file;
    /**
     * @var int
     */
    private int $line;
    /**
     * @var array
     */
    private array $trace;
    /**
     * @var int
     */
    private int $count;

    /**
     * @param array $error
     * @return Deprecation
     */
    public static function fromArray(array $error): self
    {
        return new self(
            $error['type'],
            $error['message'],
            $error['file'],
            $error['line'],
            $error['trace']
        );
    }

    /**
     * Deprecation constructor.
     * @param int $type
     * @param string $message
     * @param string $file
     * @param int $line
     * @param array $trace
     * @param int $count
     */
    private function __construct(
        int $type,
        string $message,
        string $file,
        int $line,
        array $trace,
        int $count = 1
    ) {
        if (E_USER_DEPRECATED !== $type && E_DEPRECATED !== $type) {
            throw TypeNotDeprecation::create($type);
        }

        $this->type = $type;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
        $this->trace = $trace;
        $this->count = $count;
    }

    /**
     * @return Deprecation
     */
    public function increment(): self
    {
        return new self(
            $this->type,
            $this->message,
            $this->file,
            $this->line,
            $this->trace,
            $this->count + 1
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::KEY_TYPE => $this->type,
            self::KEY_MESSAGE => $this->message,
            self::KEY_FILE => $this->file,
            self::KEY_LINE => $this->line,
            self::KEY_TRACE => $this->trace,
            self::KEY_COUNT => $this->count,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($property): bool
    {
        return array_key_exists($property, $this->toArray());
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        $properties = $this->toArray();

        return $properties[$offset];
    }

    /**
     * {@inheritDoc}
     * @throws ImmutablePropertyModification
     */
    public function offsetSet($offset, $value)
    {
        throw ImmutablePropertyModification::create($offset, serialize($value));
    }

    /**
     * {@inheritDoc}
     * @throws ImmutablePropertyModification
     */
    public function offsetUnset($offset): void
    {
        throw ImmutablePropertyUnset::create($offset);
    }
}