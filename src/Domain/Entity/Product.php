<?php

namespace App\Domain\Entity;

class Product
{
    public function __construct(
        private string $id,
        private string $name,
        private int $term,
        private float $interestRate,
        private int $amount,
    ) {
    }

    // mutators

    public function getId(): string
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