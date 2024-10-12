<?php

namespace App\Application\UseCase;

use App\Application\Dto\ClientDto;
use App\Application\Exception\ApplicationException;
use App\Application\Factory\ClientDtoFactory;
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
     *     lastName?:string,
     *     name?:string,
     *     age?:int,
     *     city?:string,
     *     state?:string,
     *     zip?:string,
     *     ssn?:string,
     *     fico?:int,
     *     email?:string,
     *     phone?:string,
     *     monthIncome?:int,
     * } $data
     * @throws ApplicationException
     */
    public function createClient(array $data): ClientDto
    {
        try {
            $client = Client::createFromArray(
                array_merge($data, ['id' => $this->uuidGenerator->generate()])
            );

            $this->repository->save($client);

            return ClientDtoFactory::createFromEntity($client);
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}