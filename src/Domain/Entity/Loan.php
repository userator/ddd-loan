<?php

namespace App\Domain\Entity;

final class Loan
{
    private const STATE = 'NY';
    private const RATE = 11.49;

    public function __construct(
        private string $id,
        private Client $client,
        private Product $product,
    ) {
    }

    public function getId(): string
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