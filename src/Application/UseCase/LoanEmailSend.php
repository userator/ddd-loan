<?php

namespace App\Application\UseCase;

use App\Application\Service\EmailSender;
use App\Domain\Event\LoanIssued;

class LoanEmailSend
{
    private const FROM = 'notifyer@company.com';
    private const SUBJECT = 'Решение о выдаче кредита';
    private const TEXT = 'Уважаемый %s вам выдан кредит %s на сумму %s под ставку %s годовых';

    public function __construct(
        private EmailSender $emailSender,
    ) {
    }

    public function sendEmail(LoanIssued $event): void
    {
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
    }
}