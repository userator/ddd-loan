<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainException;

class Email
{
    public function __construct(
        private string $value,
    ) {
        if (false === filter_var(trim($this->value), FILTER_VALIDATE_EMAIL)) {
            throw new DomainException(sprintf('Invalid email [%s]', $this->value));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
