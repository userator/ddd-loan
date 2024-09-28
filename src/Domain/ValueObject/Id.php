<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainException;

class Id
{
    public const UUID_REGEX = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';

    public function __construct(
        private string $value,
    ) {
        if (false === (bool)preg_match(self::UUID_REGEX, $this->value)) {
            throw new DomainException(sprintf('Invalid ID [%s]', $this->value));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

}