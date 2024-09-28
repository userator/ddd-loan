<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepository;
use Symfony\Component\Uid\Uuid;
use Throwable;

class ProductCreate
{
    public function __construct(
        private ProductRepository $repository,
    ) {
    }

    /**
     * @param array{
     *     name:string,
     *     term:int,
     *     interestRate:float,
     *     amount:int,
     *  } $data
     * @throws ApplicationException
     */
    public function createProduct(array $data): Product
    {
        try {
            $product = Product::createFromArray(
                array_merge($data, ['id' => Uuid::v4()->toRfc4122()]),
            );

            $this->repository->save($product);

            return $product;
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}