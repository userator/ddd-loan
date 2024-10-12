<?php

namespace App\Application\UseCase;

use App\Application\Dto\ClientDto;
use App\Application\Exception\ApplicationException;
use App\Application\Factory\ClientDtoFactory;
use App\Domain\Repository\ClientRepository;

class ClientList
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
}