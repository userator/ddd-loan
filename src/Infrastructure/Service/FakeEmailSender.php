<?php

namespace App\Infrastructure\Service;

use App\Application\Service\EmailSender;

class FakeEmailSender implements EmailSender
{
    public function __construct(
        private string $path,
    ) {
    }

    public function sendEmail(string $from, string $to, string $subject, string $text): void
    {
        $file = $this->path . DIRECTORY_SEPARATOR . getmypid() . '.txt';
        file_put_contents($file, json_encode(func_get_args(), JSON_UNESCAPED_UNICODE));
    }
}