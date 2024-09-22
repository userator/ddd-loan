<?php

namespace App\Application\EventListener;

use App\Application\Exception\ApplicationException;
use App\Application\UseCase\LoanEmailSend as LoanEmailSendUseCase;
use App\Domain\Event\LoanIssued;
use Psr\Log\LoggerInterface;

class SendEmailNotification
{
    public function __construct(
        private LoanEmailSendUseCase $useCase,
        private LoggerInterface $logger,

    ) {
    }

    public function __invoke(LoanIssued $event): void
    {
        try {
            $this->useCase->sendEmail($event);
        } catch (ApplicationException $exception) {
            $this->logger->error(
                $exception->getMessage(),
                ['trace' => $exception->getTraceAsString()],
            );
        }
    }

}