<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Application\Service\SmsSender;
use App\Domain\Repository\LoanRepository;
use App\Domain\ValueObject\Id;
use Throwable;

class LoanSmsSend
{
    public const TEXT = 'Уважаемый %s вам выдан кредит %s на сумму %s под ставку %s годовых';

    public function __construct(
        private SmsSender $smsSender,
        private LoanRepository $repository,
    ) {
    }

    /**
     * @throws ApplicationException
     */
    public function sendSms(string $loanId): void
    {
        try {
            $loan = $this->repository->findById(new Id($loanId));

            $this->smsSender->sendSms(
                $loan->getClient()->getPhone()->getValue(),
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