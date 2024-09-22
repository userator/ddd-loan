<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Loan;

interface LoanRepository
{
    public function findById(string $id): ?Loan;

    /**
     * @return Loan[]
     */
    public function findAll(): array;

    public function save(Loan $entity): void;
}