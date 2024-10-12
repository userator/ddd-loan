<?php

namespace App\Application\UseCase;

use App\Application\Dto\ProductDto;
use App\Application\Exception\ApplicationException;
use App\Application\Factory\ProductDtoFactory;
use App\Domain\Repository\ProductRepository;

class ProductList
{
    public function __construct(
        private ProductRepository $repository,
    ) {
    }

    /**
     * @return ProductDto[]
     * @throws ApplicationException
     */
    public function listProducts(): array
    {
        $products = $this->repository->findAll();

        if ([] === $products) {
            throw new ApplicationException('Продукты не найдены');
        }

        return ProductDtoFactory::createFromEntities($products);
    }
}