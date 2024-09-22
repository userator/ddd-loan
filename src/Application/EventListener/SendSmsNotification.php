<?php

namespace App\Application\EventListener;

use App\Application\Exception\ApplicationException;
use App\Application\UseCase\LoanSmsSend as LoanSmsSendUseCase;
use App\Domain\Event\LoanIssued;
use Psr\Log\LoggerInterface;

class SendSmsNotification
{
    public function __construct(
        private LoanSmsSendUseCase $useCase,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(LoanIssued $event): void
    {
        try {
            $this->useCase->sendSms($event);
        } catch (ApplicationException $exception) {
            $this->logger->error(
                $exception->getMessage(),
                ['trace' => $exception->getTraceAsString()],
            );
        }
    }
}