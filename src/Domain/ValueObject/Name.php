<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainException;

class Name
{
    /**
     * @throws DomainException
     */
    public function __construct(
        private string $value,
    ) {
        $this->value = trim($this->value);

        if ('' === $this->value) {
            throw new DomainException(sprintf('Некорректное значение [%s], должно быть более 0 символов', $this->value));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
