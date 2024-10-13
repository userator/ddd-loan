<?php

namespace App\Domain\Factory;

use App\Domain\Entity\Client;
use App\Domain\Exception\DomainException;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Fico;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Phone;
use App\Domain\ValueObject\Ssn;
use DateTimeImmutable;

class ClientFactory
{
    /**
     * @param array{
     *      id?:string,
     *      lastName?:string,
     *      firstName?:string,
     *      birthday?:string,
     *      city?:string,
     *      state?:string,
     *      zip?:string,
     *      ssn?:string,
     *      fico?:int,
     *      email?:string,
     *      phone?:string,
     *      monthIncome?:int,
     *  } $data
     * @throws DomainException
     */
    public static function createFromArray(array $data): Client
    {
        if (!isset(
            $data['id'],
            $data['lastName'],
            $data['firstName'],
            $data['birthday'],
            $data['ssn'],
            $data['fico'],
            $data['email'],
            $data['phone'],
            $data['monthIncome'],
        )) {
            throw new DomainException('Invalid argument');
        }

        return new Client(
            new Id((string)$data['id']),
            (string)$data['lastName'],
            (string)$data['firstName'],
            DateTimeImmutable::createFromFormat('Y-m-d', (string)$data['birthday']),
            Address::createFromArray($data),
            new Ssn((string)$data['ssn']),
            new Fico((int)$data['fico']),
            new Email((string)$data['email']),
            new Phone((string)$data['phone']),
            (int)$data['monthIncome'],
        );
    }

    /**
     * @param array<array{
     *      id?:string,
     *      lastName?:string,
     *      firstName?:string,
     *      birthday?:string,
     *      city?:string,
     *      state?:string,
     *      zip?:string,
     *      ssn?:string,
     *      fico?:int,
     *      email?:string,
     *      phone?:string,
     *      monthIncome?:int,
     *  }> $datas
     * @return Client[]
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