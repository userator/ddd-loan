<?php

namespace App\Application\UseCase;

use App\Application\Dto\ProductDto;
use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepository;
use App\Domain\Service\UuidGenerator;
use Throwable;

class ProductCreate
{
    public function __construct(
        private ProductRepository $repository,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    /**
     * @param array{
     *     name?:string,
     *     term?:int,
     *     interestRate?:float,
     *     amount?:int,
     * } $data
     * @throws ApplicationException
     */
    public function createProduct(array $data): ProductDto
    {
        try {
            $product = Product::createFromArray(
                array_merge($data, ['id' => $this->uuidGenerator->generate()]),
            );

            $this->repository->save($product);

            return ProductDto::createFromEntity($product);
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}