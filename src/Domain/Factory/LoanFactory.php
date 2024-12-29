<?php

namespace App\Domain\Factory;

use App\Domain\Entity\Loan;
use App\Domain\Exception\DomainException;
use App\Domain\ValueObject\Id;
use DateTimeImmutable;

class LoanFactory
{

    /**
     * @param array{
     *     id?:string,
     *     client?:array<string,mixed>,
     *     product?:array<string,mixed>,
     *     issuedAt?:string,
     * } $data
     * @throws DomainException
     */
    public static function createFromArray(array $data): Loan
    {
        if (!isset(
            $data['id'],
            $data['client'],
            $data['product'],
            $data['issuedAt'],
        )) {
            throw new DomainException('Invalid argument');
        }

        return new Loan(
            new Id((string)$data['id']),
            ClientFactory::createFromArray((array)$data['client']),
            ProductFactory::createFromArray((array)$data['product']),
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string)$data['issuedAt']),
        );
    }

    /**
     * @param array<array{
     *     id?:string,
     *     client?:array<string,mixed>,
     *     product?:array<string,mixed>,
     *     issuedAt?:string,
     * }> $datas
     * @return Loan[]
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