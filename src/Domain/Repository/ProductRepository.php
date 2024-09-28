<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Product;
use App\Domain\ValueObject\Id;

interface ProductRepository
{
    public function findById(Id $id): ?Product;

    /**
     * @return Product[]
     */
    public function findAll(): array;

    public function save(Product $entity): void;
}