<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Loan;
use App\Domain\Repository\LoanRepository;
use App\Infrastructure\Trait\FilePersister;

class FileLoanRepository implements LoanRepository
{
    use FilePersister;

    public function __construct(
        private string $path,
    ) {
    }

    public function findById(string $id): ?Loan
    {
        return current(
            array_filter(
                $this->read(),
                fn(Loan $item) => $id === $item->getId(),
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

    public function save(Loan $entity): void
    {
        $data = $this->read();

        $data[] = $entity;

        $data = array_reduce(
            $data,
            function (array $lines, Loan $line) {
                $lines[$line->getId()] = $line;
                return $lines;
            },
            [],
        );

        $this->write(array_values($data));
    }
}