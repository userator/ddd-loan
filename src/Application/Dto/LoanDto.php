<?php

namespace App\Application\Dto;

final class LoanDto
{
    public function __construct(
        private string $id,
        private string $clientId,
        private string $productId,
        private float $interestRate,
    ) {
    }

    // mutators

    public function getId(): string
    {
        return $this->id;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getInterestRate(): float
    {
        return $this->interestRate;
    }

    // business logic

    /**
     * @return array{
     *     id:string,
     *     client:string,
     *     product:string,
     *     interestRate:float,
     * }
     */
    public function castToArray(): array
    {
        return get_object_vars($this);
    }
}