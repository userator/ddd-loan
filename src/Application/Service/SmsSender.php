<?php

namespace App\Application\Service;

use App\Application\Exception\ApplicationException;

interface SmsSender
{
    /**
     * @throws ApplicationException
     */
    public function sendSms(string $phone, string $message): void;
}