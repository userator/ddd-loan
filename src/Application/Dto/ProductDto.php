<?php

namespace App\Application\Dto;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Product as ProductEntity;

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

    public static function createFromEntity(ProductEntity $entity): self
    {
        return new self(
            $entity->getId()->getValue(),
            $entity->getName(),
            $entity->getTerm(),
            $entity->getInterestRate(),
            $entity->getAmount()
        );
    }

    /**
     * @param array{
     *     id?:string,
     *     name?:string,
     *     term?:int,
     *     interestRate?:float,
     *     amount?:int,
     * } $data
     * @throws ApplicationException
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
            throw new ApplicationException('Invalid argument');
        }

        return new self(
            $data['id'],
            $data['name'],
            $data['term'],
            $data['interestRate'],
            $data['amount'],
        );
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