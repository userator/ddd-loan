<?php

namespace App\Domain\Factory;

use App\Domain\Entity\Product;
use App\Domain\Exception\DomainException;
use App\Domain\ValueObject\Id;

class ProductFactory
{
    /**
     * @param array{
     *     id?:string,
     *     name?:string,
     *     term?:int,
     *     interestRate?:float,
     *     amount?:int,
     * } $data
     * @throws DomainException
     */
    public static function createFromArray(array $data): Product
    {
        if (!isset(
            $data['id'],
            $data['name'],
            $data['term'],
            $data['interestRate'],
            $data['amount'],
        )) {
            throw new DomainException('Invalid argument');
        }

        return new Product(
            new Id((string)$data['id']),
            (string)$data['name'],
            (int)$data['term'],
            (float)$data['interestRate'],
            (int)$data['amount'],
        );
    }

    /**
     * @param array<array{
     *     id?:string,
     *     name?:string,
     *     term?:int,
     *     interestRate?:float,
     *     amount?:int,
     * }> $datas
     * @return Product[]
     * @throws DomainException
     */
    public static function createFromArrays(array $datas): array
    {
        return array_map(
            fn(array $data) => self::createFromArray($data),
            $datas
        );
    }
}