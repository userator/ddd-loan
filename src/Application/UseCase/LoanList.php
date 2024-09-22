<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Loan;
use App\Domain\Repository\LoanRepository;

class LoanList
{
    public function __construct(
        private LoanRepository $repository,
    ) {
    }

    /**
     * @return Loan[]
     * @throws ApplicationException
     */
    public function listLoans(): array
    {
        $loans = $this->repository->findAll();

        if ([] === $loans) {
            throw new ApplicationException('Займы не найдены');
        }

        return $loans;
    }
}