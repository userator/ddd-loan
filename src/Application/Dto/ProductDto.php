<?php

namespace App\Application\Dto;

class ProductDto
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

    // business logic

    /**
     * @return array{
     *     id:string,
     *     name:string,
     *     term:int,
     *     interestRate:float,
     *     amount:int
     * }
     */
    public function castToArray(): array
    {
        return get_object_vars($this);
    }
}