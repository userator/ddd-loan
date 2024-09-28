<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Loan;
use App\Domain\Repository\LoanRepository;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Trait\FilePersister;

class FileLoanRepository implements LoanRepository
{
    use FilePersister;

    public function __construct(
        private string $path,
    ) {
    }

    public function findById(Id $id): ?Loan
    {
        return current(
            array_filter(
                $this->read(),
                static fn(Loan $item) => $id->getValue() === $item->getId()->getValue(),
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
                $lines[$line->getId()->getValue()] = $line;
                return $lines;
            },
            [],
        );

        $this->write(array_values($data));
    }
}