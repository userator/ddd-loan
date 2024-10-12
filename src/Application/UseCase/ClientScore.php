<?php

namespace App\Application\UseCase;

use App\Application\Dto\ClientDto;
use App\Application\Exception\ApplicationException;
use App\Application\Factory\ClientDtoFactory;
use App\Domain\Repository\ClientRepository;
use App\Domain\Service\ScoreRandomizer;
use App\Domain\ValueObject\Id;
use Throwable;

class ClientScore
{
    public function __construct(
        private ClientRepository $repository,
        private ScoreRandomizer $randomizer,
    ) {
    }

    /**
     * @return ClientDto[]
     * @throws ApplicationException
     */
    public function findClients(): array
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
    public function scoreClient(string $clientId): bool
    {
        try {
            $client = $this->repository->findById(new Id($clientId));
        } catch (Throwable $exception) {
            throw new ApplicationException(sprintf('Клиент не найден по ID [%s]', $clientId), $exception);
        }

        if (null === $client) {
            throw new ApplicationException(sprintf('Клиент не найден по ID [%s]', $clientId));
        }

        return $client->score($this->randomizer);
    }
}