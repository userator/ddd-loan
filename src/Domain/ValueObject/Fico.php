<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainException;

class Fico
{
    public const MIN = 300;
    public const MAX = 850;

    /**
     * @throws DomainException
     */
    public function __construct(
        private int $value,
    ) {
        if ($this->value < self::MIN || $this->value > self::MAX) {
            throw new DomainException(sprintf('Invalid FICO [%s]', $this->value));
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }
}