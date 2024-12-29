<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainException;

class Ssn
{
    public const SSN_REGEX = '/^\d{3}-\d{2}-\d{4}$/';

    /**
     * @throws DomainException
     */
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