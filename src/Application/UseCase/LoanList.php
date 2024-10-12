<?php

namespace App\Application\UseCase;

use App\Application\Dto\LoanDto;
use App\Application\Exception\ApplicationException;
use App\Application\Factory\LoanDtoFactory;
use App\Domain\Repository\LoanRepository;

class LoanList
{
    public function __construct(
        private LoanRepository $repository,
    ) {
    }

    /**
     * @return LoanDto[]
     * @throws ApplicationException
     */
    public function listLoans(): array
    {
        $loans = $this->repository->findAll();

        if ([] === $loans) {
            throw new ApplicationException('Займы не найдены');
        }

        return LoanDtoFactory::createFromEntities($loans);
    }
}