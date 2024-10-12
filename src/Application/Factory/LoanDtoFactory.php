<?php

namespace App\Application\Factory;

use App\Application\Dto\LoanDto;
use App\Domain\Entity\Loan;

class LoanDtoFactory
{
    public static function createFromEntity(Loan $entity): LoanDto
    {
        return new LoanDto(
            $entity->getId()->getValue(),
            $entity->getClient()->getId()->getValue(),
            $entity->getProduct()->getId()->getValue(),
            $entity->calcInterestRate(),
        );
    }

    /**
     * @param Loan[] $entities
     * @return LoanDto[]
     */
    public static function createFromEntities(array $entities): array
    {
        return array_map(
            static fn(Loan $entity) => self::createFromEntity($entity),
            $entities,
        );
    }
}