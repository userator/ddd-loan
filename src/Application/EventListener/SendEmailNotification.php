<?php

namespace App\Application\EventListener;

use App\Application\Exception\ApplicationException;
use App\Application\Service\EmailSender;
use App\Domain\Event\LoanIssued;
use Psr\Log\LoggerInterface;

class SendEmailNotification
{
    private const FROM = 'notifyer@company.com';
    private const SUBJECT = 'Решение о выдаче кредита';
    private const TEXT = 'Уважаемый %s вам выдан кредит %s на сумму %s под ставку %s годовых';

    public function __construct(
        private EmailSender $emailSender,
        private LoggerInterface $logger,

    ) {
    }

    public function __invoke(LoanIssued $event): void
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
        } catch (ApplicationException $exception) {
            $this->logger->error(
                $exception->getMessage(),
                ['trace' => $exception->getTraceAsString()],
            );
        }
    }

}