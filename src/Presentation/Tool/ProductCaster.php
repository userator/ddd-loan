<?php

namespace App\Presentation\Tool;

use App\Domain\Entity\Product;

class ProductCaster
{
    /**
     * @param Product[] $entities
     * @return array<array{
     *      id:string,
     *      name:string,
     *      term:int,
     *      interestRate:float,
     *      amount:int
     *  }>
     */
    public static function batchCastToArray(array $entities): array
    {
        return array_map(
            static fn(Product $entity) => [
                'id' => $entity->getId()->getValue(),
                'name' => $entity->getName(),
                'term' => $entity->getTerm(),
                'interestRate' => $entity->getInterestRate(),
                'amount' => $entity->getAmount()
            ],
            $entities,
        );
    }
}