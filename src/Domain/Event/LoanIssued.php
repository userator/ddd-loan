<?php

namespace App\Domain\Event;

class LoanIssued
{
    public function __construct(
        private string $loanId,
    ) {
    }

    public function getLoanId(): string
    {
        return $this->loanId;
    }
}