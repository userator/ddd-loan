<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Application\Service\EmailSender;
use App\Domain\Event\LoanIssued;
use Throwable;

class LoanEmailSend
{
    public const FROM = 'notifyer@company.com';
    public const SUBJECT = 'Решение о выдаче кредита';
    public const TEXT = 'Уважаемый %s вам выдан кредит %s на сумму %s под ставку %s годовых';

    public function __construct(
        private EmailSender $emailSender,
    ) {
    }

    /**
     * @throws ApplicationException
     */
    public function sendEmail(LoanIssued $event): void
    {
        try {
            $this->emailSender->sendEmail(
                self::FROM,
                $event->getLoan()->getClient()->getEmail()->getValue(),
                self::SUBJECT,
                sprintf(
                    self::TEXT,
                    $event->getLoan()->getClient()->getFullName(),
                    $event->getLoan()->getProduct()->getName(),
                    $event->getLoan()->getProduct()->getAmount(),
                    $event->getLoan()->calcInterestRate(),
                ),
            );
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}