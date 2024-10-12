<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Id;
use DateTimeImmutable;

final class Loan
{
    public const STATE = 'NY';
    public const RATE = 11.49;

    public function __construct(
        private Id $id,
        private Client $client,
        private Product $product,
        private DateTimeImmutable $issuedAt,
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getIssuedAt(): DateTimeImmutable
    {
        return $this->issuedAt;
    }

    // business logic

    /**
     * Клиентам из Калифорнии увеличиваем процентную ставку на 11.49%
     */
    public function calcInterestRate(): float
    {
        return Loan::STATE !== $this->client->getAddress()->getState()
            ? $this->product->getInterestRate()
            : $this->product->getInterestRate() + Loan::RATE;
    }
}