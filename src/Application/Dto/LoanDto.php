<?php

namespace App\Application\Dto;

final class LoanDto
{
    public const ISSUED_AT_FORMAT = 'd.m.Y H:i:s';

    public function __construct(
        private string $id,
        private string $clientId,
        private string $productId,
        private float $interestRate,
        private string $issuedAt,
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

    public function getIssuedAt(): string
    {
        return $this->issuedAt;
    }

    // business logic

    /**
     * @return array{
     *     id:string,
     *     client:string,
     *     product:string,
     *     interestRate:float,
     *     issuedAt:string,
     * }
     */
    public function castToArray(): array
    {
        return get_object_vars($this);
    }
}