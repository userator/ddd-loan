<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepository;
use Symfony\Component\Uid\UuidV4;
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
            $product = new Product(
                (new UuidV4())->toRfc4122(),
                (string)$data['name'],
                (int)$data['term'],
                (float)$data['interestRate'],
                (int)$data['amount'],
            );

            $this->repository->save($product);

            return $product;
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}