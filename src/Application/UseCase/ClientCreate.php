<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Client;
use App\Domain\Repository\ClientRepository;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Fico;
use App\Domain\ValueObject\Phone;
use App\Domain\ValueObject\Ssn;
use Symfony\Component\Uid\UuidV4;
use Throwable;

class ClientCreate
{
    public function __construct(
        private ClientRepository $repository,
    ) {
    }

    /**
     * @param array{
     *       lastName:string,
     *       name:string,
     *       age:int,
     *       addressCity:string,
     *       addressState:string,
     *       addressZip:string,
     *       ssn:string,
     *       fico:int,
     *       phone:string,
     *       email:string,
     *       monthIncome:int,
     * } $data
     * @throws ApplicationException
     */
    public function createClient(array $data): Client
    {
        try {
            $client = new Client(
                (new UuidV4())->toRfc4122(),
                (string)$data['lastName'],
                (string)$data['name'],
                (int)$data['age'],
                new Address(
                    (string)$data['addressCity'],
                    (string)$data['addressState'],
                    (string)$data['addressZip'],
                ),
                new Ssn((string)$data['ssn']),
                new Fico((int)$data['fico']),
                new Email((string)$data['email']),
                new Phone((string)$data['phone']),
                (int)$data['monthIncome'],
            );

            $this->repository->save($client);

            return $client;
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}