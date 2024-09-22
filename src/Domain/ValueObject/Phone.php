<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainException;

class Phone
{
    private const PHONE_REGEX = '/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/';

    public function __construct(
        private string $value,
    ) {
        if (false === (bool)preg_match(self::PHONE_REGEX, $this->value)) {
            throw new DomainException(sprintf('Invalid city [%s]', $this->value));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

}
