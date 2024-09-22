<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainException;

class Fico
{
    private const MIN = 300;
    private const MAX = 850;

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