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
     *      interestRate:float,
     *  }>
     */
    public static function batchCastToArray(array $entities): array
    {
        return array_map(
            static fn(Loan $entity) => [
                'id' => $entity->getId()->getValue(),
                'client' => $entity->getClient()->getId()->getValue(),
                'product' => $entity->getProduct()->getId()->getValue(),
                'interestRate' => $entity->calcInterestRate(),
            ],
            $entities,
        );
    }
}