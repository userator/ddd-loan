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
    }

    /**
     * @param array<mixed> $data
     * @throws DomainException
     */
    public static function createFromArray(array $data): self
    {
        if (!isset(
            $data['id'],
            $data['name'],
            $data['term'],
            $data['interestRate'],
            $data['amount'],
        )) {
            throw new DomainException('Invalid argument');
        }

        return new self(
            new Id((string)$data['id']),
            (string)$data['name'],
            (int)$data['term'],
            (float)$data['interestRate'],
            (int)$data['amount'],
        );
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