<?php

namespace App\Application\EventListener;

use App\Application\Exception\ApplicationException;
use App\Application\Service\SmsSender;
use App\Domain\Event\LoanIssued;
use Psr\Log\LoggerInterface;

class SendSmsNotification
{
    private const TEXT = 'Уважаемый %s вам выдан кредит %s на сумму %s под ставку %s годовых';

    public function __construct(
        private SmsSender $smsSender,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(LoanIssued $event): void
    {
        try {
            $this->smsSender->sendSms(
                $event->getLoan()->getClient()->getPhone()->getValue(),
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