<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepository;

class ProductList
{
    public function __construct(
        private ProductRepository $repository,
    ) {
    }

    /**
     * @return Product[]
     * @throws ApplicationException
     */
    public function listProducts(): array
    {
        $products = $this->repository->findAll();

        if ([] === $products) {
            throw new ApplicationException('Продукты не найдены');
        }

        return $products;
    }
}