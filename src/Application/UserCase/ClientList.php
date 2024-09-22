<?php

namespace App\Application\UserCase;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Client;
use App\Domain\Repository\ClientRepository;

class ClientList
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
}