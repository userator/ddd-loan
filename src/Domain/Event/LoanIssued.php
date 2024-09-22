<?php

namespace App\Domain\Event;

use App\Domain\Entity\Loan;

class LoanIssued
{
    public function __construct(
        private Loan $loan,
    ) {
    }

    public function getLoan(): Loan
    {
        return $this->loan;
    }

}