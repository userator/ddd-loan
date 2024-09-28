<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Client;
use App\Domain\Repository\ClientRepository;
use App\Domain\ValueObject\Id;
use Throwable;

class ClientModify
{
    public function __construct(
        private ClientRepository $repository,
    ) {
    }

    /**
     * @return Client[]
     * @throws ApplicationException
     */
    public function listClients(): array
    {
        $clients = $this->repository->findAll();

        if ([] === $clients) {
            throw new ApplicationException('Клиентов не найдено');
        }

        return $clients;
    }

    /**
     * @throws ApplicationException
     */
    public function findClient(string $clientId): Client
    {
        $client = $this->repository->findById(new Id($clientId));

        if (null === $client) {
            throw new ApplicationException(sprintf('Клиент не найден по ID [%s]', $clientId));
        }

        return $client;
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
     *  } $data
     * @throws ApplicationException
     */
    public function modifyClient(string $clientId, array $data): Client
    {
        try {
            $client = Client::createFromArray(
                array_merge($data, ['id' => $clientId])
            );

            $this->repository->save($client);

            return $client;
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}