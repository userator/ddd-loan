<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainException;

class Ssn
{
    private const SSN_REGEX = '/^(?!666|000|9\d{2})\d{3}-(?!00)\d{2}-(?!0{4})\d{4}$/';

    public function __construct(
        private string $value,
    ) {
        if (false === (bool)preg_match(self::SSN_REGEX, $this->value)) {
            throw new DomainException(sprintf('Invalid SSN [%s]', $this->value));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

}