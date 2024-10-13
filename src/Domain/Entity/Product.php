<?php

namespace App\Domain\Entity;

use App\Domain\Exception\DomainException;
use App\Domain\ValueObject\Id;

class Product
{
    public function __construct(
        private Id $id,
        private string $name,
        private int $term,
        private float $interestRate,
        private int $amount,
    ) {
        $this->name = trim($this->name);

        if ('' === $this->name) {
            throw new DomainException(sprintf('Некорректное название [%s], должно быть более 0 символов', $this->name));
        }

        if (0 >= $this->term) {
            throw new DomainException(sprintf('Некорректный срок [%s], должен быть больше 0', $this->term));
        }

        if (0.0 >= $this->interestRate) {
            throw new DomainException(sprintf('Некорректная ставка [%s], должна быть больше 0.0', $this->interestRate));
        }

        if (0 >= $this->amount) {
            throw new DomainException(sprintf('Некорректная сумма [%s], должна быть больше 0', $this->amount));
        }
    }

    // mutators

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTerm(): int
    {
        return $this->term;
    }

    public function getInterestRate(): float
    {
        return $this->interestRate;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}