<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Application\Service\SmsSender;
use App\Domain\Event\LoanIssued;
use Throwable;

class LoanSmsSend
{
    public const TEXT = 'Уважаемый %s вам выдан кредит %s на сумму %s под ставку %s годовых';

    public function __construct(
        private SmsSender $smsSender,
    ) {
    }

    /**
     * @throws ApplicationException
     */
    public function sendSms(LoanIssued $event): void
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
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}