<?php
declare(strict_types=1);

namespace Tests\Utils\UnitTestBundle\Message;


use JsonSerializable;

final class CreateUnitTestMessage implements JsonSerializable
{
    public const KEY_SUBJECT = 'test_subject';

    private string $testSubject;

    /**
     * CreateUnitTestMessage constructor.
     * @param string $testSubject
     */
    public function __construct(string $testSubject)
    {
        $this->testSubject = $testSubject;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTestSubject();
    }

    /**
     * @return string
     */
    public function getTestSubject(): string
    {
        return $this->testSubject;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return [
            self::KEY_SUBJECT => $this->getTestSubject(),
        ];
    }
}