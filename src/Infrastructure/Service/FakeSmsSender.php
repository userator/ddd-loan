<?php

namespace App\Infrastructure\Service;

use App\Application\Service\SmsSender;

class FakeSmsSender implements SmsSender
{
    public function __construct(
        private string $path,
    ) {
    }

    public function sendSms(string $phone, string $message): void
    {
        $file = $this->path . DIRECTORY_SEPARATOR . getmypid() . '.txt';
        file_put_contents($file, json_encode(func_get_args(), JSON_UNESCAPED_UNICODE));
    }

}