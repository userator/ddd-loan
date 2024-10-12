<?php

namespace App\Infrastructure\Service;

use App\Application\Service\EmailSender;
use App\Infrastructure\Exception\InfrastructureException;

class FileEmailSender implements EmailSender
{
    public function __construct(
        private string $path,
    ) {
    }

    /**
     * @throws InfrastructureException
     */
    public function sendEmail(string $from, string $to, string $subject, string $text): void
    {
        $content = json_encode(func_get_args(), JSON_UNESCAPED_UNICODE);

        if (false === $content) {
            throw new InfrastructureException('Fail content encode');
        }

        $file = $this->path . DIRECTORY_SEPARATOR . getmypid() . '.txt';

        if (false === file_put_contents($file, $content)) {
            throw new InfrastructureException('Fail content write');
        }
    }
}