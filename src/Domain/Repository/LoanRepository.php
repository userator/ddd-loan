<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Loan;
use App\Domain\ValueObject\Id;

interface LoanRepository
{
    public function findById(Id $id): ?Loan;

    /**
     * @return Loan[]
     */
    public function findAll(): array;

    public function save(Loan $entity): void;
}