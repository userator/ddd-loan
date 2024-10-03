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
        return current(
            array_filter(
                $this->persister->read(),
                static fn(Client $item) => $id->getValue() === $item->getId()->getValue(),
            )
        ) ?: null;
    }

    /**
     * @inheritDoc
     * @throws InfrastructureException
     */
    public function findAll(): array
    {
        return $this->persister->read();
    }

    /**
     * @throws InfrastructureException
     */
    public function save(Client $entity): void
    {
        $data = $this->persister->read();

        $data[] = $entity;

        $data = array_reduce(
            $data,
            function (array $lines, Client $line) {
                $lines[$line->getId()->getValue()] = $line;
                return $lines;
            },
            [],
        );

        $this->persister->write(array_values($data));
    }
}
