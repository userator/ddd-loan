<?php

namespace App\Infrastructure\Repository;

use App\Application\Exception\InfrastructureException;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepository;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Service\FilePersister;

class FileProductRepository implements ProductRepository
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
    public function findById(Id $id): ?Product
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
    public function save(Product $entity): void
    {
        $data = $this->persister->read();

        $data[$entity->getId()->getValue()] = $entity;

        $this->persister->write($data);
    }
}
