<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Client;
use App\Domain\Repository\ClientRepository;
use App\Domain\Service\Randomizer;
use App\Domain\ValueObject\Id;

class ClientScore
{
    public function __construct(
        private ClientRepository $repository,
        private Randomizer $randomizer,
    ) {
    }

    /**
     * @return Client[]
     * @throws ApplicationException
     */
    public function findClients(): array
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
    public function scoreClient(string $clientId): bool
    {
        $client = $this->repository->findById(new Id($clientId));

        if (null === $client) {
            throw new ApplicationException(sprintf('Клиент не найден по ID [%s]', $clientId));
        }

        return $client->checkPossibility($this->randomizer);
    }
}