<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Application\Service\EmailSender;
use App\Domain\Repository\LoanRepository;
use App\Domain\ValueObject\Id;
use Throwable;

class LoanEmailSend
{
    public const FROM = 'notifyer@company.com';
    public const SUBJECT = 'Решение о выдаче кредита';
    public const TEXT = 'Уважаемый %s вам выдан кредит %s на сумму %s под ставку %s годовых';

    public function __construct(
        private EmailSender $emailSender,
        private LoanRepository $repository,
    ) {
    }

    /**
     * @throws ApplicationException
     */
    public function sendEmail(string $loanId): void
    {
        try {
            $loan = $this->repository->findById(new Id($loanId));

            $this->emailSender->sendEmail(
                self::FROM,
                $loan->getClient()->getEmail()->getValue(),
                self::SUBJECT,
                sprintf(
                    self::TEXT,
                    $loan->getClient()->getFullName(),
                    $loan->getProduct()->getName(),
                    $loan->getProduct()->getAmount(),
                    $loan->calcInterestRate(),
                ),
            );
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}