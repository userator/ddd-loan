<?php

namespace App\Presentation\Tool;

use App\Domain\Entity\Loan;

class LoanCaster
{
    /**
     * @param Loan[] $entities
     * @return array<array{
     *      id:string,
     *      client:string,
     *      product:string,
     *  }>
     */
    public static function batchCastToArray(array $entities): array
    {
        return array_map(
            fn(Loan $entity) => [
                'id' => $entity->getId(),
                'client' => $entity->getClient()->getId(),
                'product' => $entity->getProduct()->getId(),
                'interestRate' => $entity->calcInterestRate(),
            ],
            $entities,
        );
    }
}