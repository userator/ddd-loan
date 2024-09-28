<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Client;
use App\Domain\Repository\ClientRepository;
use App\Domain\Service\UuidGenerator;
use Throwable;

class ClientCreate
{
    public function __construct(
        private ClientRepository $repository,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    /**
     * @param array{
     *     lastName:string,
     *     name:string,
     *     age:int,
     *     city:string,
     *     state:string,
     *     zip:string,
     *     ssn:string,
     *     fico:int,
     *     phone:string,
     *     email:string,
     *     monthIncome:int,
     * } $data
     * @throws ApplicationException
     */
    public function createClient(array $data): Client
    {
        try {
            $client = Client::createFromArray(
                array_merge($data, ['id' => $this->uuidGenerator->generate()])
            );

            $this->repository->save($client);

            return $client;
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}