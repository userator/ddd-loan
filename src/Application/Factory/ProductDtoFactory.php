<?php

namespace App\Application\Factory;

use App\Application\Dto\ProductDto;
use App\Domain\Entity\Product;

class ProductDtoFactory
{
    public static function createFromEntity(Product $entity): ProductDto
    {
        return new ProductDto(
            $entity->getId()->getValue(),
            $entity->getName(),
            $entity->getTerm(),
            $entity->getInterestRate(),
            $entity->getAmount()
        );
    }

    /**
     * @param Product[] $entities
     * @return ProductDto[]
     */
    public static function createFromEntities(array $entities): array
    {
        return array_map(
            static fn(Product $entity) => self::createFromEntity($entity),
            $entities,
        );
    }
}