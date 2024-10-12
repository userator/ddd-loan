<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Loan;
use App\Domain\Repository\LoanRepository;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Exception\InfrastructureException;
use App\Infrastructure\Service\FilePersister;

class FileLoanRepository implements LoanRepository
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
    public function findById(Id $id): ?Loan
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
    public function save(Loan $entity): void
    {
        $data = $this->persister->read();

        $data[$entity->getId()->getValue()] = $entity;

        $this->persister->write($data);
    }
}