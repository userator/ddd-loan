<?php

namespace App\Application\Service;

use App\Application\Exception\ApplicationException;

interface EmailSender
{
    /**
     * @throws ApplicationException
     */
    public function sendEmail(string $from, string $to, string $subject, string $text): void;
}