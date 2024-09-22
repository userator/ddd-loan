<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Product;

interface ProductRepository
{
    public function findById(string $id): ?Product;

    /**
     * @return Product[]
     */
    public function findAll(): array;

    public function save(Product $entity): void;
}