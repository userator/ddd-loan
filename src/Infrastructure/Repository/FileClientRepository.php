<?php

namespace App\Infrastructure\Repository;

use App\Application\Exception\InfrastructureException;
use App\Domain\Entity\Client;
use App\Domain\Repository\ClientRepository;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Service\FilePersister;

class FileClientRepository implements ClientRepository
{

    public function __construct(
        private FilePersister $persister,
    ) {
    }

    public static function createFromPath(string $path): self
    {
        return new self(
            new FilePersister($path),
        );
    }

    /**
     * @throws InfrastructureException
     */
    public function findById(Id $id): ?Client
    {
        return $this->persister->read()[$id->getValue()] ?? null;
    }

    /**
     * @inheritDoc
     * @throws InfrastructureException
     */
    public function findAll(): array
    {
        return array_values($this->persister->read());
    }

    /**
     * @throws InfrastructureException
     */
    public function save(Client $entity): void
    {
        $data = $this->persister->read();

        $data[$entity->getId()->getValue()] = $entity;

        $this->persister->write($data);
    }
}
