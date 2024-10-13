<?php

namespace App\Application\UseCase;

use App\Application\Dto\ClientDto;
use App\Application\Exception\ApplicationException;
use App\Application\Factory\ClientDtoFactory;
use App\Domain\Factory\ClientFactory;
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
     * @return ClientDto[]
     * @throws ApplicationException
     */
    public function listClients(): array
    {
        $clients = $this->repository->findAll();

        if ([] === $clients) {
            throw new ApplicationException('Клиентов не найдено');
        }

        return ClientDtoFactory::createFromEntities($clients);
    }

    /**
     * @throws ApplicationException
     */
    public function findClient(string $clientId): ClientDto
    {
        try {
            $client = $this->repository->findById(new Id($clientId));
        } catch (Throwable $exception) {
            throw new ApplicationException(sprintf('Клиент не найден по ID [%s]', $clientId), $exception);
        }

        if (null === $client) {
            throw new ApplicationException(sprintf('Клиент не найден по ID [%s]', $clientId));
        }

        return ClientDtoFactory::createFromEntity($client);
    }

    /**
     * @param array{
     *     lastName?:string,
     *     firstName?:string,
     *     birthday?:string,
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
    public function modifyClient(string $clientId, array $data): ClientDto
    {
        try {
            $client = ClientFactory::createFromArray(
                array_merge($data, ['id' => $clientId])
            );

            $this->repository->save($client);

            return ClientDtoFactory::createFromEntity($client);
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}