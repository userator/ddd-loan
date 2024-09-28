<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Client;
use App\Domain\Repository\ClientRepository;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Trait\FilePersister;

class FileClientRepository implements ClientRepository
{
    use FilePersister;

    public function __construct(
        private string $path,
    ) {
    }

    public function findById(Id $id): ?Client
    {
        return current(
            array_filter(
                $this->read(),
                static fn(Client $item) => $id->getValue() === $item->getId()->getValue(),
            )
        ) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        return $this->read();
    }

    public function save(Client $entity): void
    {
        $data = $this->read();

        $data[] = $entity;

        $data = array_reduce(
            $data,
            function (array $lines, Client $line) {
                $lines[$line->getId()->getValue()] = $line;
                return $lines;
            },
            [],
        );

        $this->write(array_values($data));
    }
}
